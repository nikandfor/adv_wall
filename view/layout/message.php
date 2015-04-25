<div class="message msg_<?= $params['type'] ?>">
<?php
	if(isset($params['code'])){
		switch($params['code']){
			case 'adv_fail':
				echo "Не удалось добавить или изменить объявление";
				break;
			case 'adv_success':
				echo "Объявление успешно обновлено";
				break;
		}
	}elseif(isset($params['msg'])){
		echo $params['msg'];
	}else{
		echo "Something goes wrong";
	}
?>
</div>
