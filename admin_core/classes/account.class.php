<?php
class account extends core {

    function __construct() {
        global $db;
        $this->db = $db;
    }

    public function getID() {
        if(isset($_SESSION['uid']) && $_SESSION["uid"] != "0" && is_numeric($_SESSION["uid"])):
            return $_SESSION['uid'];
        else:
            return false;
        endif;
        return false;
    }

    public function userInfo($id = '') {
        if($id != '' && is_numeric($id)):
            //
        else:
            $id = $this->getID();
        endif;

        $query = $this->db->prepare("SELECT * FROM `smallurl_users` where `id` = :uid  LIMIT 1");

        $params = array(":uid" => $id,);
        $query->execute($params);
        if(isset($_SESSION['uid'])) {
            return $query->FetchObject();
        } else {
            //return false;
        }

    }


    public function EnableUser($uid) {
        if(is_numeric($uid)) {
            $query = $this->db->prepare("UPDATE `smallurl_users` SET `enabled` = '1' WHERE `id` = :uid");
            $params = array(":uid" => $uid,);
            $query->execute($params);
        }
    }

    public function DisableUser($uid) {
        if(is_numeric($uid)) {
            $query = $this->db->prepare("UPDATE `smallurl_users` SET `enabled` = '0' WHERE `id` = :uid");
            $params = array(":uid" => $uid,);
            $query->execute($params);
        }
    }

    public function haspriv($priv) {
        $uid = $this->getID();

        $query = $this->db->prepare("SELECT * FROM `smallurl_access` where `id` = :uid LIMIT 1");
        $params = array(":uid" => $uid,);
        $query->execute($params);
        $q = $query->FetchObject();

        if(isset($q->$priv) && $q->$priv) {
            return true;
        } else {
            return false;
        }

    }

    public function SetPassword($uid, $password) {
        if(is_numeric($uid)) {
            if($this->haspriv("admin_users_modify")) {
                $salt = $this->genString();
                $text = $password;
                $hash = $salt."$".md5($salt.$text.$salt.$salt);

                $query = $this->db->prepare("UPDATE `smallurl_users` SET `password` = :hash WHERE `id` = :uid");
                $params = array(":uid" => $uid, ":hash" => $hash);
                $query->execute($params);
            }
        }
    }
	
	public function getLevel($uid = false) {
		if (!$uid) {
			$uid = $this->getID();
		}
		// Get their role.
		$query = $this->db->prepare("SELECT * FROM `smallurl_users` WHERE `id` = :uid LIMIT 1");
		$params = array(":uid"=>$uid);
		$query->execute($params);
		$q = $query->FetchObject();
		$r = $q->role;

		// Get their level.
		$query = $this->db->prepare("SELECT * FROM `smallurl_roles` WHERE `id` = :roleid LIMIT 1");
		$params = array(":roleid"=>$r);
		$query->execute($params);
		$q = $query->FetchObject();
		
		if (isset($q->level)) {
			return $q->level;
		} else {
			return false;
		}
	}
}