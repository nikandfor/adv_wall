<?php
function w_append(){
?>
	<div class=widget_append>
		<div class=w_fields>
		<?php
			w_select('aact', ['buy', 'sell']);
			w_select('city', ['buy', 'sell']);
			w_input('price', ['type' => 'number']);
			w_select('make', ['make1', 'model1']);
		?>
		</div>
		<div class=w_btn>
		</div>
	</div>
<?
}
?>
