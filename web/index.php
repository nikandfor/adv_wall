<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

include('../conf/defines.php');

function arg_get($n, $v = FALSE){
	if(isset($_GET[$n]))
		return $_GET[$n];
	return $v;
}
function file_render($f, $params){
	ob_start();

	include("../view/".$f);
	$res = ob_get_contents();

	ob_end_clean();

	return $res;
}
function cond_add_val(&$cc, $key, $dbkey = FALSE){
	if($dbkey === FALSE)
		$dbkey = $key;

	if(isset($_GET[$key])){
		$a = mysql_escape_string($_GET[$key]);
		$cc[] = "`$dbkey` = '$a'";
	}
}
function cond_add_set(&$cc, $key, $dbkey = FALSE){
	if($dbkey === FALSE)
		$dbkey = $key;

	if(isset($_GET[$key])){
		$v = $_GET[$key];
		if(is_array($v)){
			$cc1 = array();
			foreach($v as $c){
				$c = mysql_escape_string($c);
				$cc1[] = "`$dbkey` = '$c'";
			}
			$cc[] = "(".implode(" OR ", $cc1).")";
		}else{
			$v = mysql_escape_string($v);
			$cc[] = "`$dbkey` = '$v'";
		}
	}
}
function cond_add_mm(&$cc, $key, $dbkey = FALSE){
	if($dbkey === FALSE)
		$dbkey = $key;

	if(isset($_GET[$key])){
		$v = $_GET[$key];
		$p = array();
		if(is_int($v)){
			$cc[] = "`$dbkey` = '$v'";
		}else{
			if(isset($v['min'])){
				$c = intval($v['min']);
				$p[] = "`$dbkey` >= '$c'";
			}
			if(isset($v['max'])){
				$c = intval($v['max']);
				$p[] = "`$dbkey` <= '$c'";
			}
			$cc[] = "(".implode(" AND ", $p).")";
		}
	}
}
function db_connect(){
	$mysqli = new mysqli("localhost", "pulscen", "qwe", "pulscen");
	if($mysqli->connect_errno){
		exit(1);
	}
	$mysqli->query("SET NAMES utf8");
	return $mysqli;
}
include('../model/db/db.php');
include('../model/db/advs.php');
include('../model/adv_list/main.php');
include('../model/adv_list/imgs.php');
include('../model/user/main.php');

$GLOBALS['users'] = new Users();
$GLOBALS['imgs'] = new ImageStore();

function render_log_hmenu(){
	$u = $GLOBALS['users']->currentUser();
	return file_render('user/hmenu.php', array('logged' => $u != null? $u->isLogged(): false, 'name' => $u != null? $u->name: ""));
}

if(isset($_GET['j'])){
	$r = arg_get("j");
	$a = arg_get("args");
	$args = json_decode($a, true);
	$adv = new AdvList();
//	var_dump($_GET);
	if($r == "adv_list"){
		$cont = file_render('adv_list/main.php', array('advs' => $adv->getAdvs($args), 'props' => $adv->getInputs()));
	}elseif($r == "adv_manage"){
		$cont = $adv->manageAdv($args);
		if(isset($cont['adv_data'])){
			$cont['html_adv'] = file_render('adv_list/adv.php', array_merge($cont['adv_data'], array('props' => $adv->getInputs())));
			unset($cont['adv_data']);
		}
		$cont = json_encode($cont);
	}elseif($r == "adv_template"){
		$cont = file_render('adv_list/adv.php', array_merge($adv->getAdvTemplate(), array('props' => $adv->getInputs())));
	}elseif($r == "login"){
		$cont = array('status' => 'FAIL');

		$u = $GLOBALS['users']->currentUser();
		if($u != null){
			if($u->login()){
				$cont['status'] = 'OK';
				$cont['html_hmenu'] = render_log_hmenu();
			}
		}

		$cont = json_encode($cont);
	}elseif($r == "logout"){
		$cont = array('status' => 'FAIL');

		$u = $GLOBALS['users']->currentUser();
		if($u != null){
			$u->logout();

			$cont['status'] = 'OK';
			$cont['html_hmenu'] = render_log_hmenu();
		}

		$cont = json_encode($cont);
	}

	echo $cont;
}else{
	$r = arg_get("r", "adv_list");

	if($r == "user"){
	}else{
		$adv = new AdvList();
	//	$title = $adv->getTitle();

		$filter = arg_get("filter", array());

		$sidebar = file_render("adv_list/filter.php", array('filters' => $adv->getFilters()));
		$content = file_render("adv_list/main.php", array('advs' => $adv->getAdvs($filter), 'props' => $adv->getInputs()));
	}

	$log_hmenu = render_log_hmenu();
	$wind_append = file_render('adv_list/append.php', array('filters' => $adv->getInputs()));
	$wind_login = file_render('user/login_form.php', array());
	echo file_render('layout/main.php', array(/*'title' => $title, */'log_hmenu' => $log_hmenu, 'side' => $sidebar, 'cont' => $content, 'after' => array($wind_append, $wind_login)));
}

?>
