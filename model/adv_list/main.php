<?php
class AdvList {
	protected $db;
	protected $user;
	
	function __construct(){
		$this->db = new Advs();
		$this->user = $GLOBALS['users']->currentUser();
	}

	public static function cmp($a, $b){
		return $a['sort'] - $b['sort'];
	}
	function manageAdv($d){
		$u = $this->user;
		if($u == null || !$u->isLogged())
			return array('status' => 'fail', 'message' => 'login');

		$adv = null;
		if($d['act'] == 'add'){
			$adv = $this->db->appendAdv($this->user->id);
		}else if($d['act'] == 'rm' || $d['act'] == 'edit'){
			$adv = $this->db->loadAdv($d['id']);
		}

		$props = array();
		if(isset($d['props']))
			foreach($d['props'] as $pr_n => $pr_v){
				list($pr_id) = sscanf($pr_n, "prop_%d");
				$props[$pr_id] = $pr_v;
			}

		$ims = isset($d['ims'])? $d['ims']: array();

		if($d['act'] == 'add' || $d['act'] == 'edit'){
			$adv->change($props, $ims);
		}else if($d['act'] == 'rm'){
			$adv->remove();
		}

		$adv_data = $this->getAdv1($this->db->loadOne($adv->get_id()));

		return array('status' => 'OK', 'adv_data' => $adv_data);
	}
	function getFilters(){
		$filts = array();
		$fields = $this->getFields();
		foreach($fields as $prid => $pr){
			if($pr['sort_filter'] <= 0)
				continue;

			$f = array('type' => $pr['widget_type'], 'class' => '', 'id' => "prop_${pr['id']}", 'label' => $pr['name'], 'sort' => $pr['sort_filter']);
			if(isset($pr['vals'])){
				$f['vals'] = $pr['vals'];
				uasort($f['vals'], "AdvList::cmp");
			}
			$filts[] = $f;
		}
		uasort($filts, "AdvList::cmp");

		return $filts;
	}
	function getInputs(){
		$filts = array();
		$fields = $this->getFields();
		foreach($fields as $prid => $pr){
			$f = array('type' => $pr['widget_type'], 'class' => '', 'id' => "prop_${pr['id']}", 'label' => $pr['name'], 'sort' => $pr['sort_filter']);
			if(isset($pr['vals'])){
				$f['vals'] = $pr['vals'];
				uasort($f['vals'], "AdvList::cmp");
			}
			$filts[$prid] = $f;
		}
		uasort($filts, "AdvList::cmp");

		return $filts;
	}
	function getFields(){
		return $this->db->loadFields();
	}
	function searchAdvs($q){
		$dbf = $this->getFields();
		$props = array();
		$fields = array();

		$l = preg_split('/[\s,]+/', $q);
	//	$qres = array();
		foreach($l as $e_i => $e){
			foreach($dbf as $pr_id => $pr){
				if($pr['kind'] != 'props')
					continue;
				foreach($pr['vals'] as $v_id => $v_b){
				//	echo "cmp '{$v_b['val']}' and '$e'<br>\n";
					if($v_b['val'] == $e){
						$props[$pr_id] = array($v_id);
					//	$qres[] = $e;
						continue 3;
					}
				}
			}
		}

		$list = $this->db->loadList($props, $fields);

		$ans = array();
		foreach($list as $id => $a){
			$ans[] = $this->getAdv1($a);
		}

		return $ans;
	}
	function getAdvs($f){
		$dbf = $this->getFields();
		$props = array();
		$fields = array();
		if(isset($f['props']))
			foreach($f['props'] as $pr_n => $pr_v){
				if(count($pr_v) == 0)
					continue;
				list($pr_id) = sscanf($pr_n, "prop_%d");
				if(!isset($dbf[$pr_id]))
					continue;
				if($dbf[$pr_id]['kind'] == 'props')
					$props[$pr_id] = $pr_v;
				elseif($dbf[$pr_id]['kind'] == 'fields')
					$fields[$pr_id] = $pr_v;
			}
		$list = $this->db->loadList($props, $fields);

		$ans = array();
		foreach($list as $id => $a){
			$ans[] = $this->getAdv1($a);
		}

		return $ans;
	}
	function getAdv1($a){
		$dbf = $this->getFields();
		$q = array('id' => $a['id'], 'act' => (isset($a['act'])? $a['act']: 'edit'), 'name' => $a['name'], 'view_type' => (isset($a['view_type'])? $a['view_type']: 'full'));
		$q['addit_class'] = (isset($a['addit_class'])? $a['addit_class']: "");
		$t = array();
		foreach($a['props'] as $pr_id => $pr_data){
			$t[] = array('label' => $dbf[$pr_id]['name'], 'val' => $dbf[$pr_id]['vals'][$pr_data['val']]['val'], 'val_id' => $pr_data['val'], 'prop' => $pr_data['prop']);
		}
		//	$t[] = array('label' => 'label2', 'val' => "val2", 'id' => 'id2');
		$q['table'] = $t;
		$q['ims'] = array();
		uasort($a['ims'], "AdvList::cmp");
		foreach($a['ims'] as $im){
			$q['ims'][] = array('src' => IMAGES_DIR."/{$im['path']}", 'id' => $im['id']);
		}
		$u = $GLOBALS['users']->getUser($a['owner']);
		$q['contact'] = array('name' => $u->name, 'email' => $u->email);
		$q['description'] = json_encode($a);
		//	$q['description'] = json_encode(array('props' => $dbf, 'data' => $a));

		return $q;
	}
	function getAdvTemplate(){
		$adv = $this->db->loadAdv(1);
		$a = $this->db->loadOne($adv->get_id());
		$a['id'] = '#';
		$a['act'] = "add";
		$a['name'] = 'new';
		$a['view_type'] = 'edit';
		$a['addit_class'] = 'a_item_template';
		$a['ims'] = array();
		return $this->getAdv1($a);
	}
}
?>
