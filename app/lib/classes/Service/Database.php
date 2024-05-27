<?php

namespace Service;

use Exceptions\InvalidTableException;
use PDO;
use PDOStatement;

class Database
{
    protected static ?Database $instance = null;
    protected PDO $pdo;

    private PDOStatement $statement;

    public function __construct()
    {
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $dsn = 'sqlsrv:Server=' . DB_HOST . ';Database=' . DB_NAME . ';ConnectionPooling=0;TrustServerCertificate=1';
        $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
    }

    /** Singleton */
    /** @return Database */
    public static function new(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    /** Prepares PDO query, binds the values to the query and executes it.  */
    public function bindAndExecute(string $query, array $values = []): PDOStatement
    {
        // initialize database
        $this->statement = $this->pdo->prepare($query);

        // binding all variables from attribute
        if (!empty($values)) {
            foreach ($values as $key => $value) {
                $key += 1; // using 1-based index for parameter binding
                $this->statement->bindValue($key, $value);
            }
        }

        try {
            $this->statement->execute();
        } catch (\PDOException $exception) {
             throw new \PDOException($exception);
        }

        return $this->statement;

    }

    /** Validates the given query */
    public function validateQuery(string $query, bool $message = false): bool
    {
        try {
            $result = $this->pdo->query($query);
            if ($result === false || $result->rowCount() == 0) {
                return false;
            }
        } catch (\PDOException $exception) {
            if ($message) {
                throw new \PDOException($exception);
            }
            return false;
        }

        return true;
    }

    /** Check if the given table exists in the database. */
    public function tableExists(string $table): bool
    {
        $query = "SELECT CASE 
                        WHEN EXISTS (SELECT 1 
                                     FROM INFORMATION_SCHEMA.TABLES 
                                     WHERE TABLE_NAME = :table 
                                     AND TABLE_SCHEMA = 'dbo') 
                        THEN 1 
                        ELSE 0 
                    END AS table_exists;";

        $statement = $this->pdo->prepare($query);
        $statement->bindValue(':table', $table);

        if (!$statement->execute() || $statement->fetchColumn() == 0) {
            throw new InvalidTableException($table);
        }

        return true;
    }

    // TODO: implement validations for rows & columns

}