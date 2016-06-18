<?php

class Session
{
    protected static $instance;

    protected function __construct()
    {

    }

    protected function __clone()
    {

    }

    public static function getInstance(): Session
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * Lấy dữ liệu từ Session
     * @param String $session_key Key của Session
     * @return bool
     */
    public static function get($session_key = null)
    {
        if (!is_null($session_key) && isset($_SESSION[$session_key])) {
            return $_SESSION[$session_key];
        } elseif (is_null($session_key) && isset($_SESSION)) {
            return $_SESSION;
        }
        return false;
    }


    /***
     * Thêm dữ liệu mới vào Key trong Session, nên dữ liệu cũ nếu tồn tại sẽ được sử dụng lại.
     * @param String $session_key Key của Session
     * @param Mixed $data Data của Session
     * @return Session
     */
    public static function insert($session_key = null, $data = null): Session
    {
        $instance = self::getInstance();
        if (!is_null($session_key) && !isset($_SESSION[$session_key])) {
            $_SESSION[$session_key] = $data;
        } else {
            $old_session = $_SESSION[$session_key];
            $_SESSION[$session_key] = $data;
            $_SESSION[$session_key] = array_merge($_SESSION[$session_key], $old_session);
        }
        return $instance;
    }

    /**
     * Cập nhật dữ liệu mới vào Key trong Session, không giữ lại dữ liệu cũ
     * @param String $session_key Key của Session
     * @param Mixed $data Data của Session
     * @return Session
     */
    public static function update($session_key = null, $data = null): Session
    {
        $instance = self::getInstance();
        if (!is_null($session_key) && isset($_SESSION[$session_key])) {
            $_SESSION[$session_key] = $data;
        } elseif (!is_null($session_key)) {
            self::insert($session_key, $data);
        }
        return $instance;
    }

    /**
     * Xóa Session có Key chỉ định, nếu không có sẽ xóa hết
     * @param String $session_key Key của Session
     * @return Session
     */
    public static function destroy($session_key = null): Session
    {
        $instance = self::getInstance();
        if (is_null($session_key)) {
            session_destroy();
        } else {
            session_unset($_SESSION[$session_key]);
        }
        return $instance;
    }
}