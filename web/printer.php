<pre>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

$pollProps = function(){
	$props = array();
	$ims = array();
	$data = array();
	foreach($_POST as $k => $v){
		if(preg_match('/prop_\\d+/', $k) === 1){
			$props[$k] = $v;
		}elseif(preg_match('/image_(?P<k>\\w+)_(?P<n>\\d+)/', $k, $mch) === 1){
			$ims[$mch['n']][$mch['k']] = $v;
		}else{
			$data[$k] = $v;
		}
	}
	$data['props'] = $props;
	$data['ims'] = $ims;

	return $data;
};

echo "GET\n";
print_r($_GET);
echo "POST\n";
print_r($_POST);
echo "FILES\n";
print_r($_FILES);
echo "COOKIE\n";
print_r($_COOKIE);

echo "args\n";
$args = $pollProps();
print_r($args);

?>
</pre>
