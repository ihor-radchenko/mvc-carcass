<?php
/**
 * Created by PhpStorm.
 * User: Ihor
 * Date: 14.10.2017
 * Time: 20:56
 */

namespace App;

abstract class Model
{
    const TABLE = '';

    protected $id;

    public static function findAll(): array
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . static::TABLE;
        return $dbConnect->query($sql, static::class);
    }

    public static function findById(int $id): self
    {
        $dbConnect = DataBase::getInstance();
        $sql = 'SELECT * FROM ' . static::TABLE . ' WHERE id = :id';
        $result = $dbConnect->query($sql, static::class, ['id' => $id]);
        return $result[0];
    }

    public function insert()
    {
        $arrayProp = get_object_vars($this);
        array_pop($arrayProp);
        foreach ($arrayProp as $k => $v) {
            $arrayPropMod[':' . $k] = $v;
        }

        $sql = 'INSERT INTO ' . static::TABLE . ' (' .
            implode(', ', array_keys($arrayProp)) .
            ') VALUES ('.  implode(', ', array_keys($arrayPropMod)) .')';
        $dbConnect = DataBase::getInstance();
        $dbConnect->execute($sql, $arrayProp);
    }
}