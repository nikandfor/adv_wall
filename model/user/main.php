<?php
class User {
	public $id;
	public $email;
	public $name;

	protected $salt;
	protected $pass;

	protected $logged;

	function __construct($us, $id){
		$this->id = $id;
		$d = $us->userData($id);
		$this->email = $d['email'];
		$this->name = $d['name'];
		$this->pass = $d['pass'];
		$this->salt = $d['salt'];

		$this->logged = null;
	}
	function login(){
		if(isset($_POST['l']) && isset($_POST['p'])){
			$l = $_POST['l'];
			$p = $_POST['p'];

			$q = md5($p.$this->salt);
			if($q == $this->pass){
				$t = $this->log_token($l);
				setcookie('l', $l, $this->cookie_log_time());
				setcookie('t', $t, $this->cookie_log_time());

				return $this->logged = true;
			}
		}

		return false;
	}
	function logout(){
		$this->logged = false;
		setcookie('t', 'logged_out', $this->cookie_log_time());
	}
	function isLogged(){
		if($this->logged !== null)
			return $this->logged;

		if(isset($_COOKIE['l']) && isset($_COOKIE['t'])){
			$l = $_COOKIE['l'];
			$t = $_COOKIE['t'];

			$t1 = $this->log_token($l);

			if($t1 == $t)
				return $this->logged = true;
		}

		return $this->logged = $this->login();
	}
	function cookie_log_time(){
		return time() + 3600 * 24 * 10;
	}
	function repeat_cookie(){
		setcookie('l', $_COOKIE['l'], cookie_log_time());
		setcookie('t', $_COOKIE['t'], cookie_log_time());
	}
	function log_token($l){
		return md5($l.$_SERVER['HTTP_USER_AGENT'].$this->salt.$this->pass);
	}
}
class Users {
	protected $tn_users = 'users';

	protected $db;
	protected $users;
	protected $cur_user;

	function userData($id){
		$us = $this->getUsers();
		if(isset($us[$id]))
			return $us[$id];
		return null;
	}
	function findUserLog($log){
		$us = $this->getUsers();
		foreach($us as $id => $u){
			if($u['email'] == $log)
				return new User($this, $id);
		}

		return null;
	}
	function currentUser(){
		if($this->cur_user == null){
			if(isset($_COOKIE['l']))
				return $this->cur_user = $this->findUserLog($_COOKIE['l']);
			elseif(isset($_POST['l']))
				return $this->cur_user = $this->findUserLog($_POST['l']);
			elseif(isset($_GET['l']))
				return $this->cur_user = $this->findUserLog($_GET['l']);
		}
		return $this->cur_user;
	}
	function getUser($id){
		return new User($this, $id);
	}
	function getUsers(){
		if($this->users != null)
			return $this->users;

		$this->users = array();

		$q = $this->db->query("SELECT * FROM `{$this->tn_users}`");
		while($a = $q->fetch_assoc()){
			$this->users[$a['id']] = $a;
		}

		return $this->users;
	}
	function __construct(){
		$this->db = new DB();
		$this->users = null;
		createTable($this->tn_users, array(array('email', 'varchar(100)'), array('name', 'varchar(100)'), array('pass', 'varchar(40)'), array('sail', 'varchar(40)')));
	}
}
?>
