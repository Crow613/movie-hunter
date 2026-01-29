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
    public static string $tableName = '';

    public static false|string $tableNameAlias =false;
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
     * @var string
     */
    protected string $groupBy = '';


    /**
     * @var array|string
     */
    protected string | array $select  = ' * ';
    protected  string | array $join  = '';

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
     * @param  string  $column
     * @param  string  $operator
     * @param  mixed  $value
     * @return $this
     */
    public function where(string $column, string $operator, mixed $value): static
    {
        $this->where[] = [$column,$operator,$value];
        return $this;
    }

    /**
     * @param  string  $column
     * @param  string  $value
     * @return $this
     */
    public function whereLike(string $column, string $value): static
    {
        $this->where[] = [$column, "LIKE", $value];
        return $this;
    }

    /**
     * @param  string|array|false  $columns
     * @return $this
     */
    public function select(string|array|false $columns = false): static
    {
        if($columns) $this->select = $columns;
        return $this;
    }
    public function join(string|array|false $columns = false): static
    {
        if ($columns)
            $this->join .= " " . $columns . " ";
        return $this;
    }
    public function groupBy(string $column): static
    {
        $this->groupBy = " GROUP BY {$column} ";
        return $this;
    }
    public function from(string $table, string $alias = ""): static
    {
        if (!empty($table)){
            self::$tableName = $table;
            self::$tableNameAlias = $alias;
        }
        return $this;
    }
    /**
     * @return PDOStatement | array
     */
    public function get(): PDOStatement | array
    {
        $table = static::$tableName;
        $alias = static::$tableNameAlias;

         if (!empty(self::$tableName)) {
              $table = self::$tableName;
             if (self::$tableNameAlias) $alias = self::$tableNameAlias;
         }
        $pdo = self::connection();

        if (is_array($this->select)) $this->select = implode(", ",$this->select);

        if ($alias) $table .= " ". $alias;

        $sql = "SELECT " . $this->select . " FROM " . $table;

        if (!empty($this->join)) $sql .=" ". $this->join;

        $values = [];

        if (!empty($this->where)) {
            $conditions = [];
            foreach ($this->where as $value) {
                [$column, $operator , $val] = $value;
                $conditions[] = "{$column} {$operator} ?";
                $values[] = $val;
            }
            $sql .= ' WHERE '. implode(' AND ', $conditions);
        }
        if ($this->groupBy) $sql .= $this->groupBy;

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