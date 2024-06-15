<?php

namespace Service;

use Exceptions\InvalidTableException;
use PDO;
use PDOException;
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

    /**
     * Retrieves the singleton instance of the Database class.
     *
     * @return Database The Database instance.
     */
    public static function instance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    /**
     * Prepares a PDO query, binds the values to placeholders, and executes it.
     *
     * @param string $query The SQL query to prepare.
     * @param array $values The values to bind to the query parameters.
     * @return bool|PDOStatement Returns the PDOStatement object on success, or false on failure.
     */
    public function bindAndExecute(string $query, array $values = []): bool|PDOStatement
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
        } catch (PDOException $exception) {
            Error::set(['error' => 'Database error', 'message' => $exception->getMessage()]);
            return false;
        }

        return $this->statement;
    }

    /**
     * Validates the given SQL query.
     *
     * @param string $query The SQL query to validate.
     * @param bool $message Whether to throw an exception on query validation failure.
     * @return bool Returns true if the query is valid and returns rows, false otherwise.
     * @throws PDOException If the query validation fails and $message is true.
     */
    public function validateQuery(string $query, bool $message = false): bool
    {
        try {
            $result = $this->pdo->query($query);
            if ($result === false || $result->rowCount() == 0) {
                return false;
            }
        } catch (PDOException $exception) {
            if ($message) {
                throw new PDOException($exception);
            }
            return false;
        }

        return true;
    }

    /**
     * Checks if the given table exists in the database.
     *
     * @param string $table The name of the table to check.
     * @return bool Returns true if the table exists, throws InvalidTableException otherwise.
     * @throws InvalidTableException If the table does not exist.
     */
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

}