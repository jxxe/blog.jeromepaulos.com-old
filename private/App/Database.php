<?php

namespace Blog\App;

use PDO;
use PDOStatement;

abstract class Database {

    private const PATH_TO_DB = Paths::RESOURCES . '/database.sqlite3';
    private static PDO $pdo;

    private static function get_pdo(): PDO {
        return self::$pdo ?? self::$pdo = new PDO('sqlite:' . self::PATH_TO_DB);
    }

    public static function query(string $query, ?array $params = null): array {
        $statement = self::get_pdo()->prepare($query);
        $statement->execute($params);
        return $statement->fetchAll();
    }

}