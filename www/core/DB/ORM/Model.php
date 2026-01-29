<?php

namespace Core\DB\ORM;

use Core\DB\PDO\Databace;
use PDO;

class Model extends Databace
{
    public static string $tableName;

    public static function all()
    {
        $pdo = self::connection();
        $sql = " SELECT * FROM ".static::$tableName;
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param  string  $sortBY
     * @param  string  $sort
     * @return $this
     */
    public function orderBy(string $sortBY, string $sort=" ASC "): static
    {
        $this->orderBy[] = $sortBY." ".$sort;
        return  $this;
    }

    /**
     * @param  int  $limit
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param  array  $data
     * @return int
     */
    public static function create(array $data): int
    {
        $pdo = self::connection();

        $columns = array_keys($data);
        $placeholders = array_fill(0, count($data), '?');
        $values = array_values($data);

        $sql = "INSERT INTO ".static::$tableName." (".implode(',', $columns).") VALUES (".implode(', ', $placeholders).")";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        return (int)$pdo->lastInsertId();

    }

    /**
     * @param  array  $data
     * @param  int|null  $id
     * @return bool
     */
    public  function update(array $data, ?int $id = null): bool
    {
        $pdo = self::connection();
        $set = [];
        $values = [];
        foreach ($data as $col => $val) {
            $set[] = "$col = ?";
            $values[] = $val;
        }
        $sql = "UPDATE " . static::$tableName . " SET " . implode(', ', $set) . $this->getParams()["sql"];
        $params = $this->getParams()["values"];
        $params ?? $values[] = $params;

        if (is_int($id)){
            $sql .= " WHERE id = ?";
           $values[] = $id;
        }
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * @param  int|null  $id
     * @return bool
     */
    public function delete(?int $id = null): bool
    {
        $pdo = self::connection();

        $sql = "DELETE FROM " . static::$tableName;
        $values = [];
        $sql .=  $this->getParams()["sql"];
        $this->getParams()["values"] ?? $values[] = $this->getParams()["values"];
        if (is_int($id)) {
            $sql .= " WHERE id = ?";
            $values[] = $id;
        }
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($values);
    }
    /**
     * @return array|string[]
     */
    private function getParams(): array
    {
        if (empty($this->where)) return ["sql"=>"", "values"=>false];
            $conds = [];
            foreach ($this->where as $w) {
                [$col, $op, $val] = $w;
                $conds[] = "$col $op ?";
                $values[] = $val;
            }
           return [
             "sql"=>" WHERE " . implode(' AND ', $conds),
             "values"=> $values
           ];
    }
}