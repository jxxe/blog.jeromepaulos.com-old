<?php

namespace Blog\App;

use DB;

/**
 * @method static queryFirstField(string $query, mixed...$values)
 * @method static query(string $query, mixed...$values)
 * @method static queryFirstColumn(string $query, mixed...$values)
 * @method static array|null queryFirstRow(string $query, mixed...$values)
 * @method static insertUpdate(string $table, mixed[] $data)
 * @method static int insertId()
 * @method static insert(string $table, mixed[] $data)
 * @method static update(string $table, mixed[] $data, string $where, mixed...$values)
 * @method static delete(string $table, string $where, mixed...$values);
 * @method static disconnect()
 * @method static \mysqli get()
 */
abstract class Database extends DB {
}

Database::$dbName = $_ENV['DB_NAME'];
Database::$user = $_ENV['DB_USERNAME'];
Database::$password = $_ENV['DB_PASSWORD'];
Database::$host = $_ENV['DB_HOST'];
Database::$ssl = [
    'key' => $_ENV['DB_SSL_CERT_PATH'],
    'cert' => null,
    'ca_cert' => null,
    'ca_path' => null,
    'cipher' => null
];