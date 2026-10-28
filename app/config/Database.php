<?php

class Database {

    private string $host = "localhost";
    private string $dbName = "wedding_store";
    private string $username = "root";
    private string $password = "";

    private ?PDO $connection = null;

    public function connect(): PDO {
    // إذا لم يكن هناك اتصال محفوظ في $connection، حاول إنشاء اتصال جديد.
        if ($this->connection === null) {
            try {
                // الـ DSN اختصار لـ Data Source Name، وهو نص يحتوي على معلومات يحتاجها PDO لمعرفة نوع قاعدة البيانات ومكانها وكيفية الاتصال بها.
                $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";

                $this->connection = new PDO(
                    $dsn,
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );

            } catch (PDOException $e) {
                error_log($e->getMessage());

                throw new RuntimeException(
                    "Database connection failed."
                );
            }
        }

        return $this->connection;
    }
}