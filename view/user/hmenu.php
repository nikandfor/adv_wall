<?php

if($params['logged']){
?>
	<div class=log_hmenu>
		<div class=log_text>
			<?= $params['name'] ?>
		</div>
		<div class=log_menu_pos>
		<div class=log_menu_rad>
			<div class=log_menu>
				<ul class=hmenu_ul>
					<li id=hmenu_login_logout>Logout
					<li id=hmenu_login_edvs>My advs
				</ul>
			</div>
		</div>
		</div>
	</div>
	<style>
		.hmenu_ul {
			margin: 0;
			padding: 0;
		//	padding-left: 30px;
			list-style-type: none;
		}
		.hmenu_ul li {
			margin: 2px;
			padding: 5px 10px;
			cursor: pointer;
			border-top: 1px solid black;
		}
		.hmenu_ul li:hover {
			background: #bbb;
		}
		.log_hmenu {
			position: relative;
			float: right;
		//	width: 120px;
			background: #eee;
		}
		.log_text {
			padding: 7px 20px 6px;
			border: 1px solid #929292; 
		}
		.log_menu_pos {
			position: relative;
		}
		.log_menu_rad {
			display: none;
			position: absolute;
			top: -1px;
			right: -10px;
			margin-top: -50px;
			padding: 50px;
			padding-right: 10px;
			min-width: 160px;
		//	background: #eee;
			z-index: 100;
		}
		.log_menu {
			position: relative;
			padding: 0px;
			background: #ccc;
		}
		.log_hmenu:hover .log_menu_rad {
			display: block;
		}
		.log_hmenu:hover .log_menu {
		//	background: red;
		}
	</style>
<?php
}else{
?>
	<button id=login_show_button>login</button>
<?php
}

?>
