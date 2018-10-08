<?php
// NOW WITH KARATE CHOP ACTION!
class cache {
	private static $prefix;
	
	private static function construct() {
		self::$prefix = "cache_v0.1";
	}

	static function store($key,$data,$expiry = 30) {
		self::construct();
		// Stores data, Overwriting existing.
		$cname = self::kname($key);
		$cdata = base64_encode(serialize($data));
		
		// Work out expiry timestamp. Expiry is in mins.
		$esec = (int)$expiry * 60;
		$estamp = time()+$esec;
		
		xRedis::set(self::key($cname),$estamp."\n".$cdata);
	}
	static function read($key) {
		self::construct();
		// If the cache item exists it will load it else return null
		$cname = self::kname($key);
		if (self::exists($key)) {
			$fl = xRedis::get(self::key($cname));
			$ln = explode("\n",$fl);
			
			// Retrieve data.
			$dat = implode("\n",array_slice($ln,1));
			$dat = unserialize(base64_decode($dat));
			
			return $dat;
		} else {
			return null;
		}
	}
	static function clear($key) {
		self::construct();
		$cname = self::kname($key);
		if (self::exists($key)) {
			xRedis::del(self::key($cname));
		}
		return true;
	}
	static function exists($key) {
		self::construct();
		// Check if the item exists
		$cname = self::kname($key);
		if (xRedis::get(self::key($cname)) != NULL) {
			$fl = xRedis::get(self::key($cname));
			$ln = explode("\n",$fl);
			
			if (time() <= $ln[0]) {
				// Not expired.
				return true;
			} else {
				// Expired
				xRedis::del(self::key($cname)); // Delete file.
				return false; // It expired so it doesnt exist.
			}
			
		} else {
			return false;
		}
	}
	static function expiry($key) {
		self::construct();
		$cname = self::kname($key);
		// retrieves when the cache item expires.
		if (self::exists(self::key($key))) {
			$fl = xRedis::get(self::key($cname));
			$ln = explode("\n",$fl);
			return $ln[0];
		} else {
			return null;
		}
	}
	private static function key($md5) {
		return self::$prefix.":".$md5;
	}
	private static function kname($key) {
		$string = str_replace(chr(32),"_",$key);
		$string = strtolower($string);
		return $string;
	}
}