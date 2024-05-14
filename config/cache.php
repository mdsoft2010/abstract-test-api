<?php
namespace Config;

class Cache
{

    protected static $redis;

    public static function connect()
    {
        self::$redis = new \Redis();
        $host = getenv('REDIS_HOST') ?: '127.0.0.1';
        $port = getenv('REDIS_PORT') ?: 6379;
        self::$redis->connect($host, $port);
    }

    public static function get($key)
    {
        self::connect();
        return self::$redis->get($key);
    }

    public static function set($key, $value, $expired = 30)
    {
        self::connect();
        return self::$redis->set($key, $value, $expired);
    }
}
