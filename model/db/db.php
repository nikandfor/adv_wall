<?php
class DB {
	protected $c;

	function query($z){
		$q = $this->c->query($z);
		if($q === FALSE){
			echo "<b>MYSQL_ERROR:</b> {$this->c->error}<br>\n";
			echo "[$z]<br>\n";
			$tr = debug_backtrace();
			$c = $tr[0];
			echo "at {$c['class']} ({$c['file']}:{$c['line']})<br>";
		}
		return $q;
	}
	function escape_string($s){
		return $this->c->escape_string($s);
	}
	function connect(){
		$this->c = db_connect();
	}
	function close(){
		$this->c->close();
	}
	function __construct(){
		$this->connect();
	}
	function __destruct(){
		$this->close();
	}
}
?>
