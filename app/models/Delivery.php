<?php
class Delivery {
    const NEXT = ['assigned' => ['picked_up'], 'picked_up' => ['delivered', 'failed']];
    private const BASE = 'SELECT d.*,o.details,o.total,c.full_name,c.address,r.username AS rider
        FROM deliveries d JOIN orders o ON o.order_id=d.order_id JOIN customers c ON c.customer_id=o.customer_id
        JOIN users r ON r.user_id=d.rider_id';
    static function all(): array { return Database::q(self::BASE . ' ORDER BY d.delivery_id DESC')->fetchAll(); }
    static function forRider(int $uid): array { return Database::q(self::BASE . ' WHERE d.rider_id=? ORDER BY d.delivery_id DESC', [$uid])->fetchAll(); }
    static function find(int $id): ?array { return Database::q(self::BASE . ' WHERE d.delivery_id=?', [$id])->fetch() ?: null; }
    static function byOrder(int $oid): ?array { return Database::q(self::BASE . ' WHERE d.order_id=?', [$oid])->fetch() ?: null; }

    private static function tx(callable $fn): void {
        $pdo = Database::pdo(); $pdo->beginTransaction();
        try { $fn(); $pdo->commit(); } catch (Throwable $t) { $pdo->rollBack(); throw $t; }
    }
    static function assign(int $orderId, int $riderId): void {
        self::tx(function () use ($orderId, $riderId) {
            Database::q('INSERT INTO deliveries (order_id,rider_id) VALUES (?,?)', [$orderId, $riderId]);
            Order::setStatus($orderId, 'assigned');
        });
    }
    static function reassign(array $d, int $riderId): void {
        self::tx(function () use ($d, $riderId) {
            Database::q("UPDATE deliveries SET rider_id=?,status='assigned',picked_at=NULL,delivered_at=NULL WHERE delivery_id=?", [$riderId, $d['delivery_id']]);
            Order::setStatus((int)$d['order_id'], 'assigned');
        });
    }
    static function advance(array $d, string $to): bool {
        if (!in_array($to, self::NEXT[$d['status']] ?? [], true)) return false;
        $col = ['picked_up' => 'picked_at=NOW()', 'delivered' => 'delivered_at=NOW()', 'failed' => 'delivered_at=NULL'][$to]; // fixed map, not user input
        $os = ['picked_up' => 'in_transit', 'delivered' => 'delivered', 'failed' => 'pending'][$to];
        self::tx(function () use ($d, $to, $col, $os) {
            Database::q("UPDATE deliveries SET status=?,$col WHERE delivery_id=?", [$to, $d['delivery_id']]);
            Order::setStatus((int)$d['order_id'], $os);
        });
        return true;
    }
    static function delete(array $d): void {
        self::tx(function () use ($d) {
            Database::q('DELETE FROM deliveries WHERE delivery_id=?', [$d['delivery_id']]);
            Order::setStatus((int)$d['order_id'], 'pending');
        });
    }
}
