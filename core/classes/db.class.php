<?php
// DataBase Class
class db {
    static $connected = false;
    
	static function insert($table,$data,$conditional = false) {
		global $config,$smallurl;
		
		global $_SMALLURL;
		$sqli = $_SMALLURL['SQL'];
		
		$prefix = config::get('db_pref');
		
		$cols = array();
		$dat = array();
		
		// Issue when inserting data.
		foreach ($data as $col => $val) {
			$cols[] = "`".$sqli->real_escape_string($col)."`";
			$dat[] = "'".$sqli->real_escape_string($val)."'";
		}
		
		if ($conditional) {
			$cond = self::conditional($conditional);
			$result = self::query("INSERT INTO {$prefix}_{$table} (".implode(",",$cols).") VALUES(".implode(",",$dat).") WHERE {$cond};");
		} else {
			$result = self::query("INSERT INTO {$prefix}_{$table} (".implode(",",$cols).") VALUES(".implode(",",$dat).");");
		}
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
	static function get_val($table,$where,$equals,$item) {
		// This function needs deprecating. It's unrequired.
		$dat = self::get_array($table,array($where=>$equals));
		if (count($dat) > 0) {
			if (isset($dat[0][$item])) {
				return $dat[0][$item];
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	static function update($table,$data,$conditional = false) {
		global $config,$smallurl;
		
		global $_SMALLURL;
		$sqli = $_SMALLURL['SQL'];
		
		$prefix = config::get('db_pref');
		
		$set = array();
		
		foreach ($data as $col => $val) {
			$set[] = "`".$sqli->real_escape_string($col)."`='".$sqli->real_escape_string($val)."'";
		}
		if (count($set) > 0) {
			$set_str = implode(",",$set);
		}
		else {
			$set_str = $set[0];
		}
		
		if ($conditional) {
			$cond = self::conditional($conditional);
			$dat = self::query("UPDATE {$prefix}_{$table} SET {$set_str} WHERE {$cond};");
		} else {
			$dat = self::query("UPDATE {$prefix}_{$table} SET {$set_str};");
		}
		if ($dat) {
			return true;
		} else {
			return false;
		}
	}
	static function escape_string($string) {
		global $_SMALLURL;
		$sqli = $_SMALLURL['SQL'];
		return $sqli->real_escape_string($string);
	}
	static function drop($table,$conditional) {
		// You can't even use this?
	}
	static function delete($table,$conditional = false) {
		global $config,$smallurl;
		$prefix = config::get('db_pref');
		if ($conditional) {
			$cond = self::conditional($conditional);
			$dat = self::query("DELETE FROM {$prefix}_{$table} WHERE {$cond};");
		} else {
			$dat = self::query("DELETE FROM {$prefix}_{$table};");
		}

		if ($dat) {
			return true;
		} else {
			return false;
		}
	}
	static function get_bool($table,$conditional) {
		// Useless. :D
		$cond = self::conditional($conditional);
		$dat = self::query("SELECT * FROM {$prefix}_{$table} WHERE {$conditional}");
		if ($dat) {
			return true;
		}
		else {
			return false;
		}
	}
	static function get_array($table,$conditional = false,$selection = "*") {
		global $config,$smallurl;
		$prefix = config::get('db_pref');
		if ($conditional) {
			$cond = self::conditional($conditional);
			$dat = self::query("SELECT {$selection} FROM {$prefix}_{$table} WHERE {$cond};");
		} else {
			$dat = self::query("SELECT {$selection} FROM {$prefix}_{$table};");
		}
		
		$ret = array();
		if ($dat) {
			while ($row = $dat->fetch_array(MYSQL_ASSOC)) {
				$ret[] = $row;
			}
		}
		return $ret;
	}
	static function get_query($table,$conditional = false) {
		global $config,$smallurl;
		// Returns the QUERY as a String.
		$prefix = config::get('db_pref');
		if ($conditional) {
			$cond = self::conditional($conditional);
			$ret = "SELECT * FROM {$prefix}_{$table} WHERE {$cond};";
		} else {
			$ret = "SELECT * FROM {$prefix}_{$table};";
		}
		return $ret;
	}
	static function init() {
		global $smallurl,$_SMALLURL;
		@$mysqli = new mysqli(config::get('db_host'), config::get('db_user'), config::get('db_pass'), config::get('db_name'));
		if ($mysqli->connect_errno) {
			$smallurl->error('Error Connecting to SQL ['.$mysqli->connect_errno.']: '.$mysqli->connect_error,'FATAL');
			$_SMALLURL['SQL'] = false;
		} else {
			$_SMALLURL['SQL'] = $mysqli;
			//self::$connected = true;
		}
	}
	// Query has HAD to be made public
	static function query($query) {
		/*
		if(!self::$connected) {
			self::init();
		}
		*/
		global $smallurl,$_SMALLURL;
		$sqli = $_SMALLURL['SQL'];
		if ($sqli) {
			$res = $sqli->query($query);
			if (!$res) {
				$smallurl->error("(".$sqli->errno.") ".$sqli->error,'FATAL');
			}
			return $res;
		}
	}
	static function conditional($arr) {
		// Generates a SQL WHERE Query kinda...
		global $_SMALLURL;
		/*
		if(!self::$connected) {
			self::init();
		}
		*/
		
		$sqli = $_SMALLURL['SQL'];
		$full = array();
		if ($sqli) {
			foreach ($arr as $key => $content) {
				if (substr($key,-1) != ")") {
					$key = "`{$key}`";
				}
				if (!is_array($content)) {
					$cont = $sqli->real_escape_string($content);
					$full[] = "{$key}='{$cont}'";
				}
				else {
					$cont = $sqli->real_escape_string($content['val']);
					$full[] = "{$key}{$content['comp']}'{$cont}'";

				}
			}
			return implode(chr(32)."AND".chr(32),$full);
		} else {
			return '';
		}
	}
}
?>