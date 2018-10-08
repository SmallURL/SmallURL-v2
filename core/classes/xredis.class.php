<?php
class xRedis
{
    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
			$inst = new Predis\Client('tcp://'.config::get("redis_host").':6379',[
				'prefix' => config::get("redis_pref").":",
			]);
        }
        return $inst;
    }
 
    static public function __callStatic($method, $args) {
        $c = self::Instance();
        return call_user_func_array([$c, $method], $args);
    }
}
Predis\Autoloader::register();
xRedis::Instance();