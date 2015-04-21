<div class=a_status>
	<div class=st_found>
		found стопицот (<?= count($params['advs']) ?>) items
	</div>
</div>
<div class=a_list id=a_list>
	<?php
		foreach($params['advs'] as $adv){
			echo file_render("adv_list/adv.php", array_merge($adv, array('props' => $params['props'])));
		}
	?>
</div>
