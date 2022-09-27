<?php

class MyPDO extends \PDO
{
    protected static $instance = null;
    private string $dsn;
    private ?string $username;
    private ?string $passwd;
    private ?array $options;

    public function __construct($dsn, $username = null, $passwd = null, $options = null)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->options = $options;
        parent::__construct($dsn, $username, $passwd, $options);
    }

    public static function getInstance(): ?MyPDO
    {
        if (self::$instance === null) {
            $dsn = 'sqlite:'.dirname(__FILE__).'/Chinook';
            self::$instance = new self($dsn, null, null, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_CLASS,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        }
        return self::$instance;
    }

    public function execute($query, $params = []): \PDOStatement
    {
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function get($query, $params = []): array
    {
        $stmt = $this->execute($query, $params);
        return $stmt->fetchAll(\PDO::FETCH_CLASS);
    }
}
