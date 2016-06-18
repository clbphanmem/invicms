<?php namespace Cores;

class Hook
{
    protected static $instance;
    public static $actionArr = [];
    protected static $filterArr = [];

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * TODO something at defined position
     * @param string $position
     * @return Hook
     */
    public static function doAction(string $position, bool $lowestToHighest = true): Hook
    {
        $hooks = static::sortPriority(static::$actionArr[$position], $lowestToHighest);
        foreach ($hooks as $hook) {
            call_user_func($hook['callback']);
        }
        return static::getInstance();
    }

    public static function applyFilters(string $filterName, $callback, bool $runTopToBottom = true): Hook
    {
        return static::getInstance();
    }

    /**
     * Do something
     * @return mixed
     */
    public static function action(string $actionName, $callback, int $priority = 10): Hook
    {
        if ($actionName && !empty($callback)) {
            static::$actionArr[$actionName][] = [
                'priority' => $priority,
                'callback' => $callback
            ];
        }
        return static::getInstance();
    }

    public static function sortPriority(array $arr, bool $lowestToHighest = true): array
    {
        foreach ($arr as $k1 => $v1) {
            foreach ($arr as $k2 => $v2) {
                if ($lowestToHighest && $arr[$k1]['priority'] < $arr[$k2]['priority']) {
                    $temp = $arr[$k1];
                    $arr[$k1] = $arr[$k2];
                    $arr[$k2] = $temp;
                } elseif (!$lowestToHighest && $arr[$k1]['priority'] > $arr[$k2]['priority']) {
                    $temp = $arr[$k1];
                    $arr[$k1] = $arr[$k2];
                    $arr[$k2] = $temp;
                }
            }
        }
        return $arr;
    }


    /**
     * Apply Something
     * @return mixed
     */
    public static function filter(string $filterName, $callback): Hook
    {
        return static::getInstance();
    }
}