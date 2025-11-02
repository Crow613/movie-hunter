<?php

namespace Core\DB\PDO;

use PDO;

use PDOStatement;

use Symfony\Component\Dotenv\Dotenv;

abstract class Databace
{
    /**
     * @var string
     */
    public static string $tableName;
    /**
     * @var PDO|null
     */
    public  static PDO|null $connect = null;

    /**
     * @var array
     */
    protected array $where = [];

    /**
     * @var array
     */
    protected array $orderBy = [];
    /**
     * @var int
     */
    protected int $limit = 0;


    /**
     * @var array|string
     */
    public  string | array $select  = ' * ';

    /**
     * @return PDO
     */
    protected static function connection(): PDO
    {
        $env = new Dotenv();
        $env->loadEnv(__DIR__."/../../../.env");
        $host = $_ENV['DB_HOST'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $db = $_ENV['DATABASE'];

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$connect = $pdo;
            return  self::$connect;

        }catch (\PDOException $e){
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }
    /**
     * @return PDOStatement | array
     */
    public function get(): PDOStatement | array
    {
        $pdo = self::connection();
        if (is_array($this->select)){
            $this->select = implode(", ",$this->select);
        }
        $sql = "SELECT " . $this->select . " FROM " . static::$tableName;
        $values = [];
        if (!empty($this->where)) {
            $conditions = [];
            foreach ($this->where as $value) {
                [$column, $operator, $val] = $value;
                $conditions[] = "{$column} {$operator} ?";
                $values[] = $val;
            }
            $sql .= ' WHERE '. implode(' AND ',$conditions);
        }
        if(!empty($this->orderBy)) $sql .= ' ORDER BY '. implode(", ", $this->orderBy);
        if ($this->limit)  $sql .= ' LIMIT '. $this->limit;

        if (empty($values)){
            return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}