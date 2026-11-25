<?php
class User {
    static function find(int $id): ?array { return Database::q('SELECT * FROM users WHERE user_id=?', [$id])->fetch() ?: null; }
    static function byLogin(string $l): ?array { return Database::q('SELECT * FROM users WHERE username=? OR email=?', [$l, $l])->fetch() ?: null; }
    static function all(): array { return Database::q('SELECT user_id,username,email,role,created_at FROM users ORDER BY user_id')->fetchAll(); }
    static function riders(): array { return Database::q("SELECT user_id,username FROM users WHERE role='rider' ORDER BY username")->fetchAll(); }
    static function exists(string $u, string $e, int $ignore = 0): bool {
        return (bool)Database::q('SELECT 1 FROM users WHERE (username=? OR email=?) AND user_id<>?', [$u, $e, $ignore])->fetch();
    }
    static function create(string $u, string $e, string $pw, string $role): int {
        Database::q('INSERT INTO users (username,email,password_hash,role) VALUES (?,?,?,?)', [$u, $e, password_hash($pw, PASSWORD_DEFAULT), $role]);
        return (int)Database::pdo()->lastInsertId();
    }
    static function update(int $id, string $u, string $e, string $role, string $pw = ''): void {
        if ($pw !== '') Database::q('UPDATE users SET username=?,email=?,role=?,password_hash=? WHERE user_id=?', [$u, $e, $role, password_hash($pw, PASSWORD_DEFAULT), $id]);
        else Database::q('UPDATE users SET username=?,email=?,role=? WHERE user_id=?', [$u, $e, $role, $id]);
    }
    static function delete(int $id): void { Database::q('DELETE FROM users WHERE user_id=?', [$id]); }
    static function validate(array $in, int $id = 0, bool $needPw = true, bool $needRole = false): array {
        $e = []; $u = trim($in['username'] ?? ''); $m = trim($in['email'] ?? ''); $p = (string)($in['password'] ?? '');
        if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $u)) $e['username'] = '3–30 letters, digits or underscores.';
        if (!filter_var($m, FILTER_VALIDATE_EMAIL) || strlen($m) > 100) $e['email'] = 'Enter a valid email address.';
        if (($needPw || $p !== '') && !preg_match('/^(?=.*[A-Za-z])(?=.*\d).{8,72}$/', $p)) $e['password'] = 'Min 8 characters, with a letter and a digit.';
        if ($needRole && !in_array($in['role'] ?? '', ['admin', 'customer', 'rider'], true)) $e['role'] = 'Choose a role.';
        if (!$e && self::exists($u, $m, $id)) $e['username'] = 'Username or email is already taken.';
        return $e;
    }
}
