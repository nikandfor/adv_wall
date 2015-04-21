<div class="wind_back invisible" id=wind_append>
	<div class="wind_cont widget_append" id=wind_append_cont>
		<div class="wide_block">
			<input type=hidden id=append_inp_id value="">
			<input type=hidden id=append_inp_act value="add">
		<?php
			foreach($params['filters'] as $filter){
				echo file_render("adv_list/widget/inp_".$filter['type'].".php", $filter);
			}
		?>
		</div>
		<div class="wide_block">
			<button id=append_send class="button">Жмакай!</button>
			<a href=# class="link_button" id=append_cancel>не, передумал</a>
			<a href=# class="link_button" id=append_clear>давай по новой</a>
		</div>
	</div>
</div>
<style>
.wind_back {
	position: fixed;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	background: rgba(0, 0, 0, 0.7);
	display: none;
}
.wind_back.visible {
	display: block;
}
.wind_cont {
	display: table;
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	margin: auto;
	width: 500px;
	height: auto;
	background: #495d75;
	padding: 30px;
	border-radius: 4px;
}
.bl_inp {
	margin: 10px auto;
}
.button {
	margin: 10px 20px;
}
</style>
