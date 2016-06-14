<?php namespace Cores;
use Cores\Exception as Exception;

class Route {
    public static $route;
    public static $uri;
    public static $routeArray = [];

    public static function getInstance(): Route {
        if (static::$route === null) {
            static::$route = new static();
        }
        return static::$route;
    }

    /**
     * Xóa thư mục cha trên REQUEST_URI
     * @param string $route
     * @return string
     */
    public static function removeURIParentFolders(string $route): string
    {
        $parentFolders = trim(preg_replace('/\/[\w]+.php/U', '', $_SERVER['PHP_SELF']), ' /');
        $route = preg_replace('/' . preg_quote($parentFolders, '/') . '/u', '', $route);
        return trim($route, ' /');
    }

    /**
     * Lấy các biến dựa trên Route được định nghĩa
     * @param $route
     * @param $uri
     * @return array
     */
    public static function getVar(string $route, string $uri): array
    {
        $route = preg_replace('/\//', '\/', $route);
        $uri = static::filterURI($uri);
        preg_match_all('/{(.*)}/U', $route, $vars);
        $allVars = [];
        if ($vars) {
            $route = preg_replace('/{.*}/U', '(.*)', $route);
            preg_match('/^' . $route . '$/U', $uri, $values);
            if ($values) {
                unset($values[0]);
                $values = array_values($values);
                foreach ($vars[1] as $key => $var) {
                    $allVars[$var] = isset($values[$key]) ? $values[$key] : '';
                }
            }
        }
        return $allVars;
    }

    /**
     * Xóa toàn bộ Querystring trên URI
     * @param $route
     * @return string
     */
    public static function filterURI(string $route)
    {
        $route = preg_replace('/\/?\?.*/u', '', $route);
        return trim($route, ' /');
    }

    /**
     * Lấy Request URI
     * @return string|boolean
     */
    public static function getRequestURI(): string {
        return static::removeURIParentFolders($_SERVER['REQUEST_URI']);
    }

    /**
     * So sánh Route và URI
     * @param string $route
     * @param string $uri
     * @return bool
     */
    public static function compareRoute(string $route = '', string $uri = ''): bool
    {
        $route = preg_replace('/\//', '\/', $route); //Escapsing '/'
        preg_match_all('/({.*})/U', $route, $vars); //Get All Abstract Vars
        if ($vars) {
            foreach ($vars[1] as $var) {
//                $route = preg_replace('/' .preg_quote($var, '/'). '/U', '(.*)', $route);
                $route = preg_replace('/' . preg_quote($var, '/') . '/U', '[\w-]+', $route);
            }
        }
        if (preg_match('/^' . $route . '(\?.*|\/\?.*)?$/U', $uri, $uri)) {
            return true;
        }
        return false;
    }

    public static function verifyRoute(): array {
        $uri = static::getRequestURI();
        foreach (static::$routeArray as $key => $route) {
            if (static::compareRoute($route['url'], $uri)) {
                return [
                    'isMatch' => true,
                    'key' => $key
                ];
            }
        }
        return [
            'isMatch' => false
        ];
    }

    /**
     * Lấy thông tin Controller và Method
     * @param string $callable
     * @return array
     */
    public static function getControllerAndMethod(string $callable): array {
        $arr = explode('@', $callable);
        return [
            'controller' => $arr[0],
            'method' => $arr[1]
        ];
    }

    /**
     * @param string $url
     * @param callable|string $callback
     * @return Route
     */
    public static function get(string $url, $callback): Route {
        static::$routeArray[] = [
            'url' => trim($url, ' /'),
            'callback' => $callback
        ];

        return static::getInstance();
    }


    /**
     * Chạy Route
     */
    public static function dispatch() {
        $routeArray = static::$routeArray;
        $routeIndex = static::verifyRoute();
        static::$uri = static::getRequestURI();
        if ($routeIndex['isMatch'] && isset($routeArray[$routeIndex['key']]) && is_callable($routeArray[$routeIndex['key']]['callback'])) {
            $vars = static::getVar($routeArray[$routeIndex['key']]['url'], static::$uri);
            echo call_user_func_array($routeArray[$routeIndex['key']]['callback'], $vars);
        } elseif ($routeIndex['isMatch'] && isset($routeArray[$routeIndex['key']]) && !is_callable($routeArray[$routeIndex['key']]['callback'])) {
            $arr = static::getControllerAndMethod($routeArray[$routeIndex['key']]['callback']);
            $vars = static::getVar($routeArray[$routeIndex['key']]['url'], static::$uri);
            $callable = "Cores\\Controllers\\" . $arr['controller'] . '::' . $arr['method'];
            echo call_user_func_array($callable, $vars);
        } else {
            $route = static::$uri ? static::$uri : '/';
            Exception::routeNotFound($route);
            exit();
        }
    }
}