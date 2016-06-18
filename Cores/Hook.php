<?php namespace Cores;

class Hook
{
    protected static $instance;
    public static $actionArr = [];
    protected static $filterArr = [];

    /**
     * Get instance of Hook
     * @return Hook
     */
    public static function getInstance(): Hook
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Do something at defined position
     * @param string $position
     * @param bool $lowestToHighest
     * @return Hook
     */
    public static function doAction(string $position, bool $lowestToHighest = true): Hook
    {
        if (isset(static::$actionArr[$position])) {
            $hooks = static::sortPriority(static::$actionArr[$position], $lowestToHighest);
            foreach ($hooks as $hook) {
                call_user_func_array($hook['callback'], $hook['vars']);
            }
        }
        return static::getInstance();
    }

    /**
     * Apply some style
     * @param string $filterName
     * @param $callback
     * @param bool $runTopToBottom
     * @return Hook
     */
    public static function applyFilters(string $filterName, $callback, bool $runTopToBottom = true): Hook
    {
        return static::getInstance();
    }

    /**
     * Add action to array
     * @param string $actionName
     * @param $callback
     * @param int $priority
     * @param array ...$vars
     * @return Hook
     */
    public static function action(string $actionName, $callback, int $priority = 10, ...$vars): Hook
    {
        if ($actionName && !empty($callback)) {
            static::$actionArr[$actionName][] = [
                'priority' => $priority,
                'callback' => $callback,
                'vars' => $vars
            ];
        }
        return static::getInstance();
    }

    /**
     * Sort Priority of Array
     * @param array $arr
     * @param bool $lowestToHighest
     * @return array
     */
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

    /**
     * Catch error when Hook used in String
     * @return string
     */
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return '';
    }
}