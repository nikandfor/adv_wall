<div class=filters>
	<div class=wide_block>
	<?php
		foreach($params['filters'] as $filter){
			echo file_render("adv_list/widget/filt_".$filter['type'].".php", $filter);
		}
	?>
	</div>
	<div class=wide_block>
		<button class=btn_apply id=btn_filts_apply>
			Update
		</button>
	</div>
</div>

<style>
	.filters .wide_block {
		padding: 10px;
	}
	.filt {
	//	float: left;
		margin: 15px;
	//	border: 1px solid black;
	}
	.filt input[type=number] {
		width: 70px;
	}
	.filt select#val_city {
		width: 130px;
	}
	.btn_act {
		padding: 6px 15px;
		background: #779;
	}
	.btn_act.active {
		background: #f7f8f4;
	}
</style>
