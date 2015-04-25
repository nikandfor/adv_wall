<html>
<head>
	<meta charset="utf-8">
	<link rel=stylesheet type="text/css" href="css/main.css">
	<link rel=stylesheet type="text/css" href="css/adv_list.css">
	<script src="js/adv_list.js"></script>
	<title>adv list<?php/*= $params['title'] */?></title>
</head>
<body class="js_unavailable">
	<div class=wrap>
		<div class=header>
			<div class=nav>
				<div id=hmenu_login_container><?= $params['log_hmenu'] ?></div>
				<button id=append_show_button>append</button>
				<div class=a_search>
					<input type=text class=a_search_text>
					<input type=button class=a_search_send value="S">
				</div>
			</div>
			<div class=logo>
			</div>
		</div>
		<div class=body>
			<div class=menu>
				<?= $params['side'] ?>
			</div>
			<div class=main id=main_container>
				<?= $params['message'] ?>
				<?= $params['cont'] ?>
			</div>
		</div>
	</div>
		<div class=footer>
			<div class=hr></div>
			Copyright contacts
		</div>
	
	<?php
		foreach($params['after'] as $a)
			echo $a;
	?>
</body>
</html>
