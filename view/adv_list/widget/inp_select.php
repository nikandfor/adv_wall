		<div class="bl_inp bl_inp_<?= $params['type'] ?> <?= $params['class'] ?>">
			<?php if(isset($params['label'])){ ?>
				<label for=<?= $params['id'] ?>><?= $params['label'] ?></label>
			<?php } ?>
			<select id=<?= $params['id'] ?>>
				<?php
					foreach($params['vals'] as $id => $V):
				?>
						<option value=<?=$V['id']?> selected><?=$V['val']?>
				<?php
					endforeach
				?>
			</select>
		</div>
