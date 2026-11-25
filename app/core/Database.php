<?php
final class Database {
    private static ?PDO $pdo = null;
    static function pdo(): PDO {
        if (!self::$pdo) {
            $d = (require __DIR__ . '/../config.php')['db'];
            self::$pdo = new PDO("mysql:host={$d['host']};dbname={$d['name']};charset={$d['charset']}", $d['user'], $d['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }
        return self::$pdo;
    }
    /** Every query in the app goes through here: always a prepared statement. */
    static function q(string $sql, array $p = []): PDOStatement {
        $s = self::pdo()->prepare($sql);
        $s->execute($p);
        return $s;
    }
}
