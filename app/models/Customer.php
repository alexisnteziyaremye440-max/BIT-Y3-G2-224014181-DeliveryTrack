<?php
class Customer {
    static function all(): array { return Database::q('SELECT * FROM customers ORDER BY customer_id')->fetchAll(); }
    static function find(int $id): ?array { return Database::q('SELECT * FROM customers WHERE customer_id=?', [$id])->fetch() ?: null; }
    static function byUser(int $uid): ?array { return Database::q('SELECT * FROM customers WHERE user_id=?', [$uid])->fetch() ?: null; }
    static function create(string $n, string $p, string $a, ?int $uid = null): int {
        Database::q('INSERT INTO customers (user_id,full_name,phone,address) VALUES (?,?,?,?)', [$uid, $n, $p, $a]);
        return (int)Database::pdo()->lastInsertId();
    }
    static function update(int $id, string $n, string $p, string $a): void { Database::q('UPDATE customers SET full_name=?,phone=?,address=? WHERE customer_id=?', [$n, $p, $a, $id]); }
    static function delete(int $id): void { Database::q('DELETE FROM customers WHERE customer_id=?', [$id]); }
    static function validate(array $in): array {
        $e = []; $n = trim($in['full_name'] ?? ''); $p = trim($in['phone'] ?? ''); $a = trim($in['address'] ?? '');
        if (mb_strlen($n) < 2 || mb_strlen($n) > 100) $e['full_name'] = 'Name must be 2–100 characters.';
        if (!preg_match('/^\+?[0-9 ]{7,15}$/', $p)) $e['phone'] = 'Enter a valid phone number (7–15 digits).';
        if (mb_strlen($a) < 5 || mb_strlen($a) > 255) $e['address'] = 'Address must be 5–255 characters.';
        return $e;
    }
}
