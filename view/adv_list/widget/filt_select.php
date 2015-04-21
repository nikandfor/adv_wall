		<div class="bl_filter bl_filt_select <?= $params['class'] ?>">
			<?php if(isset($params['label'])){ ?>
				<label for=<?= $params['id'] ?>><?= $params['label'] ?></label>
			<?php } ?>
			<form id="<?= $params['id'] ?>">
				<ul>
				<?php
					foreach($params['vals'] as $id => $V):
				?>
						<li><input type=checkbox value=<?=$id?>><?=$V['val']?>
				<?php
					endforeach
				?>
				</ul>
			</form>
		</div>
