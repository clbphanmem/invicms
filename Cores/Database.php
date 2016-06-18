<?php namespace Cores;

class Database {
    protected static $instance;
    protected static $select;
    protected static $table;

    public static function getInstance(): Database
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public static function name()
    {
        return static::$table ?? static::table()->name();
    }

    public static function singularOrPlural(string $str): string
    {
        if (!preg_match('/^.*ies$/U', $str) && preg_match('/^.*y$/U', $str)) {
            $str = substr_replace($str, 'ies', strlen($str) - 1);
            return $str;
        } elseif (!preg_match('/^.*es$/U', $str) && preg_match('/^.*(s|x|z|ch|sh)$/U', $str)) {
            return $str . 'es';
        }
        return $str . 's';
    }

    public static function table(string $name = ''): Database
    {

        static::$table = strtolower(!empty($name) ? $name : static::singularOrPlural((new \ReflectionClass(static::getInstance()))->getShortName()));
        return static::getInstance();
    }

    public static function select(string $sql)
    {
        static::$select = $sql;
    }

    public static function limit()
    {

    }

    public static function get()
    {

    }
}