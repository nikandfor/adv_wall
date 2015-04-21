<?php
class Adv {
	protected $id;
	protected $advs;

	function __construct($advs, $i){
		$this->advs = $advs;
		$this->id = $i;
	}

	function change($props, $ims){
		$this->advs->changeAdv($this->id, $props, $ims);
	}
	function remove(){
		$this->advs->removeAdv($this->id);
	}
	function get_id(){
		return $this->id;
	}
}
class Advs {
	protected $tn_advs = 'advs';
	protected $tn_advprops = 'adv_props';
	protected $tn_advims = 'adv_imgs';
	protected $tn_prns = 'prop_names';
	protected $tn_prpr = 'props_';
//	protected $tn_fdns = 'field_names';
	protected $tn_fdpr = 'fields_';
	protected $tn_imgs = 'imgs_upload';

	protected $props;
	protected $advs;

	protected $db;

	function changeAdv($id, $p, $m){
		foreach($p as $pid => $pv){
			$this->updateProp(intval($id), intval($pid), $pv);
		}

		$up = $GLOBALS['imgs'];
		$u = $GLOBALS['users']->currentUser();
		$ims_add = array();
		$ims_rm = array();
		foreach($m as $im){
			if($im['act'] == 'add'){
				$id = $up->uploadImage($u->id, $im['fileid']);
				$ims_add[] = "('$id', '{$im['img']}', '{$im['sort']}')";
			}elseif($im['act'] == 'rm'){
				$up->removeImage($im['img']);
				$ims_rm[] = "'{$im['img']}'";
			}
		}
		if(count($ims_add) > 0){
			$vals = implode(", ", $ims_add);
			$this->db->query("INSERT INTO `{$tn_advims}` (`adv`, `img`, `sort`) VALUES $vals");
		}
		if(count($ims_rm) > 0){
			$vals = implode(", ", $ims_rm);
			$this->db->query("DELETE FROM `{$tn_advims}` WHERE `img` IN ($vals)");
		}
	}
	function removeAdv($id){
		$q = $this->db->query("SELECT `img` FROM `{$this->tn_advims}` WHERE `adv` = '$id'");
		$ims = array();
		while($a = $q->fetch_assoc())
			$ims[] = $a['img'];
		$m = $GLOBALS['imgs'];
		$m->removeImages($ims);

		$this->db->query("DELETE FROM `{$this->tn_advims}` WHERE `adv` = '$id'");
		$this->db->query("DELETE FROM `{$this->tn_advprops}` WHERE `adv` = '$id'");
		$this->db->query("DELETE FROM `{$this->tn_fdpr}int` WHERE `adv` = '$id'");
		$this->db->query("DELETE FROM `{$this->tn_fdpr}float` WHERE `adv` = '$id'");
		$this->db->query("DELETE FROM `{$this->tn_fdpr}text` WHERE `adv` = '$id'");
		$this->db->query("DELETE FROM `{$this->tn_advs}` WHERE `id` = '$id'");
	}
	function updateProp($adv, $id, $v){
		$p = $this->loadFields();
		if(!isset($p[$id]))
			throw "unexisted field";

		$k = $p[$id]['kind'];

		if($k == "props"){
			if(!isset($p[$id]['vals'][$v]))
				throw "unexisted property value";

			$q = $this->db->query("SELECT `id` FROM `{$this->tn_advprops}` WHERE `adv` = '$adv' AND `prop` = '$id' LIMIT 1");
			if($q->num_rows > 0){
				$a = $q->fetch_assoc();
				$this->db->query("UPDATE `{$this->tn_advprops}` SET `val` = '$v' WHERE `id` = '{$a['id']}'");
			}else{
				$this->db->query("INSERT INTO `{$this->tn_advprops}` (`adv`, `prop`, `val`) VALUES ('$adv', '$id', '$v')");
			}
		}else if($k == "fields"){
			$t = $p[$id]['type'];
			if($t == 'int')
				$v = intval($v);
			elseif($t == 'float')
				$v = floatval($v);
			else
				$v = $this->db->escape_string($v);

			$q = $this->db->query("SELECT `id` FROM `{$this->tn_fdpr}$t` WHERE `adv` = '$adv' AND `field` = '$id' LIMIT 1");
			if($q->num_rows > 0){
				$a = $q->fetch_assoc();
				$this->db->query("UPDATE `{$this->tn_fdpr}$t` SET `val` = '$v' WHERE `id` = '{$a['id']}'");
			}else{
				$this->db->query("INSERT INTO `{$this->tn_fdpr}$t` (`adv`, `field`, `val`) VALUES ('$adv', '$id', '$v')");
			}
		}
	}
	function appendAdv($owner){
		$this->db->query("INSERT INTO `{$this->tn_advs}` (`owner`) VALUES ('{$owner}')");
		$q = $this->db->query("SELECT LAST_INSERT_ID()");
		$a = $q->fetch_array();
		return new Adv($this, $a[0]);
	}
	function loadAdv($id){
		$q = $this->db->query("SELECT `id` FROM `{$this->tn_advs}` WHERE `id` = '$id'");
		if($q->num_rows == 0)
			throw new Exception("unexisted adv");

		return new Adv($this, $id);
	}
	function loadList($props, $fields, $args = array()){
		$status = (isset($args['status'])? $args['status']: "");
		$limit = (isset($args['limit'])? $args['limit']: "");

		$this->advs = array();
		$q = $this->db->query("SELECT * FROM `{$this->tn_advs}` WHERE ".($status != ""? "`status` = '$status'": "`status` = 'active'").($limit != ""? "LIMIT $limit": ""));
		while($a = $q->fetch_assoc()){
			$a['props'] = array();
			$a['fields'] = array();
			$a['ims'] = array();
			$this->advs[$a['id']] = $a;
		}

		$q = $this->db->query("SELECT * FROM `{$this->tn_advprops}`");
		while($a = $q->fetch_assoc()){
			if(!isset($this->advs[$a['adv']]))
				continue;

			if(isset($props[$a['prop']])){
				if(!in_array($a['val'], $props[$a['prop']])){
					//	echo "SKIP ADV {$a['adv']} COZ val {$a['val']} NOT IN (".json_encode($props).")<br>\n";
					unset($this->advs[$a['adv']]);
					continue;
				}
			}

			$this->advs[$a['adv']]['props'][$a['prop']] = $a;
		}

		$q = $this->db->query("(SELECT *, 'float' as `type` FROM `{$this->tn_fdpr}float`) UNION ".
						 "(SELECT *, 'int'   as `type` FROM `{$this->tn_fdpr}int`) UNION ".
						 "(SELECT *, 'text'  as `type` FROM `{$this->tn_fdpr}text`)");
		while($a = $q->fetch_assoc()){
			if(!isset($this->advs[$a['adv']]))
				continue;

			if(isset($fields[$a['field']])){
				$ff = $fields[$a['field']];
				$skip = FALSE;
				if($a['type'] == 'int'){
					if(isset($ff['min']) && $ff['min'] != ""){
						if($ff['min'] > $a['val']){
							$skip = TRUE;
						}
					}
					if(!$skip && isset($ff['max']) && $ff['max'] != ""){
						if($ff['max'] < $a['val']){
							$skip = TRUE;
						}
					}
				}
				if($skip){
					unset($this->advs[$a['adv']]);
					continue;
				}
			}

			$this->advs[$a['adv']]['fields'][$a['field']] = $a;
		}

		$q = $this->db->query("SELECT * FROM `{$this->tn_advims}`");
		$q = $this->db->query("SELECT `{$this->tn_advims}`.`id` as `id`, `adv`, `md5`, `path`, `sort` FROM `{$this->tn_advims}`, `{$this->tn_imgs}` WHERE `{$this->tn_advims}`.`img` = `{$this->tn_imgs}`.`id`");
		while($a = $q->fetch_assoc()){
			if(!isset($this->advs[$a['adv']]))
				continue;

			$this->advs[$a['adv']]['ims'][] = $a;
		}

		foreach($this->advs as $id => $a){
			$this->advs[$id]['name'] = "adV_".$a['id'];
		}

	//	var_dump($this->advs);
		return $this->advs;
	}
	function loadOne($id){
		$ans = array();
		$q = $this->db->query("SELECT * FROM `{$this->tn_advs}` WHERE `id` = '$id'");
		if($a = $q->fetch_assoc()){
			$ans = $a;
			$ans['props'] = array();
			$ans['fileds'] = array();
		}

		$q = $this->db->query("SELECT * FROM `{$this->tn_advprops}` WHERE `adv` = '$id'");
		while($a = $q->fetch_assoc()){
			$ans['props'][$a['prop']] = $a;
		}

		$q = $this->db->query("(SELECT *, 'float' as `type` FROM `{$this->tn_fdpr}float` WHERE `adv` = '$id') UNION ".
						 "(SELECT *, 'int'   as `type` FROM `{$this->tn_fdpr}int` WHERE `adv` = '$id') UNION ".
						 "(SELECT *, 'text'  as `type` FROM `{$this->tn_fdpr}text` WHERE `adv` = '$id')");
		while($a = $q->fetch_assoc()){
			$ans['fields'][$a['field']] = $a;
		}

		$q = $this->db->query("SELECT `{$this->tn_advims}`.`id` as `id`, `md5`, `path`, `sort` FROM `{$this->tn_advims}`, `{$this->tn_imgs}` WHERE `adv` = '$id' AND `{$this->tn_advims}`.`img` = `{$this->tn_imgs}`.`id`");
		while($a = $q->fetch_assoc()){
			$ans['ims'][] = $a;
		}

		$ans['name'] = "adV_".$ans['id'];

		return $ans;
	}
	function loadFields(){
		if(count($this->props) > 0)
			return $this->props;

		$this->props = array();
		$q = $this->db->query("SELECT * FROM `$this->tn_prns`");
		while($a = $q->fetch_assoc()){
			if($a['kind'] == 'props')
				$a['vals'] = array();
			$this->props[$a['id']] = $a;
		}
		$q = $this->db->query("(SELECT * FROM `{$this->tn_prpr}text`) UNION ".
						 "(SELECT * FROM `{$this->tn_prpr}float`) UNION ".
						 "(SELECT * FROM `{$this->tn_prpr}int`)");
		while($a = $q->fetch_assoc()){
			$this->props[$a['prop']]['vals'][$a['id']] = $a;
		}
	//	$q = $this->db->query("SELECT * FROM `{$this->tn_fdns}`");
	//	while($a = $q->fetch_assoc()){
	//		$this->props['fields'][$a['id']] = $a;
	//	}

		return $this->props;
	}
	function __construct(){
		$this->db = new DB();
	}
}
?>
