<?php
class ImageStore {
	protected $tn_imgs = 'imgs_upload';
	protected $files;
	protected $adv_ims;

	protected $db;

	protected $exts = array('image/jpeg' => 'jpg', 'image/png' => 'png');

	function uploadImage($u, $im_id){
		if(!isset($_FILES[$im_id]) || !isset($this->exts[$_FILES[$im_id]['type']]))
			return false;

		$fl = $_FILES[$im_id];
		$md5 = md5($fl['tmp_name']);
		$ext = $this->exts[$fl['type']];
		$path = "$md5.$ext";

		if(!move_uploaded_file($fl['tmp_name'], LOCAL_IMAGES_DIR."/$path"))
			return false;

		$this->db->query("INSERT INTO `{$this->tn_imgs}` (`user`, `md5`, `path`) VALUES ('$u', '$md5', '$path')");
		$q = $this->db->query("SELECT LAST_INSERT_ID()");
		$a = $q->fetch_array();

		$id = $a[0];

		return $id;
	}
	function removeImages($arr){
		foreach($arr as $el)
			$this->removeImage($el);
	}
	function removeImage($id){
		$q = $this->db->query("SELECT * FROM `{$this->tn_imgs}` WHERE `id` = '$id'");
		$a = $q->fetch_assoc();

		unlink(IMAGES_DIR."/".$a['path']);

		$this->db->query("DELETE FROM `{$this->tn_imgs}` WHERE `id` = '$id'");
	}
	function __construct(){
		$this->db = new DB();
		$this->files = null;
		$this->adv_ims = null;

		createTable($this->tn_imgs, array(array('user'), array('md5', 'varchar(34)'), array('path', 'varchar(100)')));
	}
}
?>
