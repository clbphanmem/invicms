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
    public static function doAction(string $position, bool $runTopToBottom = true): Hook
    {
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
            if (!static::$actionArr) { // Hook is not defined
                static::$actionArr[$actionName][] = [
                    'priority' => $priority,
                    'callback' => $callback
                ];
            } elseif (isset(static::$actionArr[$actionName]) && static::$actionArr[$actionName]) { // Hook is defined
                foreach (static::$actionArr[$actionName] as $key => $value) {
                    if ($value['priority'] <= $priority) {
                        static::$actionArr[$actionName][$key + 1]['priority'] = $priority;
                        static::$actionArr[$actionName][$key + 1]['callback'] = $callback;
                    } else {
                        static::$actionArr[$actionName][$key - 1]['priority'] = $priority;
                        static::$actionArr[$actionName][$key - 1]['callback'] = $callback;
                    }
                }
            }
        }
        return static::getInstance();
    }

    public static function sortPriority(array $arr): array
    {
        foreach ($arr as $k1 => $v1) {
            foreach ($arr as $k2 => $v2) {
                
            }
        }
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