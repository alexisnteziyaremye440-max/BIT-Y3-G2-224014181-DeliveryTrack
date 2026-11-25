<?php
class Order {
    const STATUSES = ['pending', 'assigned', 'in_transit', 'delivered', 'cancelled'];
    /** Role-scoped search used by both the page and the JSON API. */
    static function search(string $q, array $u): array {
        $sql = 'SELECT o.order_id,o.order_date,o.total,o.status,o.details,c.full_name,d.rider_id,r.username AS rider
                FROM orders o JOIN customers c ON c.customer_id=o.customer_id
                LEFT JOIN deliveries d ON d.order_id=o.order_id LEFT JOIN users r ON r.user_id=d.rider_id WHERE 1=1';
        $p = [];
        if ($u['role'] === 'customer') { $sql .= ' AND c.user_id=?'; $p[] = $u['user_id']; }
        if ($u['role'] === 'rider') { $sql .= ' AND d.rider_id=?'; $p[] = $u['user_id']; }
        if ($q !== '') {
            $sql .= ' AND (c.full_name LIKE ? OR o.status LIKE ? OR o.details LIKE ? OR o.order_id=?)';
            $like = '%' . $q . '%';
            array_push($p, $like, $like, $like, ctype_digit($q) ? (int)$q : 0);
        }
        return Database::q($sql . ' ORDER BY o.order_id DESC LIMIT 100', $p)->fetchAll();
    }
    static function find(int $id): ?array {
        return Database::q('SELECT o.*,c.full_name,c.phone,c.address,c.user_id AS cust_user,d.rider_id
            FROM orders o JOIN customers c ON c.customer_id=o.customer_id
            LEFT JOIN deliveries d ON d.order_id=o.order_id WHERE o.order_id=?', [$id])->fetch() ?: null;
    }
    static function create(int $cid, string $d, float $t): int {
        Database::q('INSERT INTO orders (customer_id,details,total) VALUES (?,?,?)', [$cid, $d, $t]);
        return (int)Database::pdo()->lastInsertId();
    }
    static function update(int $id, int $cid, string $d, float $t, string $s): void {
        Database::q('UPDATE orders SET customer_id=?,details=?,total=?,status=? WHERE order_id=?', [$cid, $d, $t, $s, $id]);
    }
    static function setStatus(int $id, string $s): void { Database::q('UPDATE orders SET status=? WHERE order_id=?', [$s, $id]); }
    static function delete(int $id): void { Database::q('DELETE FROM orders WHERE order_id=?', [$id]); }
    static function unassigned(): array {
        return Database::q("SELECT o.order_id,o.details,o.total FROM orders o WHERE o.status='pending'
            AND NOT EXISTS (SELECT 1 FROM deliveries d WHERE d.order_id=o.order_id) ORDER BY o.order_id")->fetchAll();
    }
}
