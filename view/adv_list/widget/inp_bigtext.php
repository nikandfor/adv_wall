		<div class="bl_inp bl_inp_<?= $params['type'] ?> <?= $params['class'] ?>">
			<?php if(isset($params['label'])){ ?>
				<label for=<?= $params['id'] ?>><?= $params['label'] ?></label>
			<?php } ?>
			<textarea id=<?= $params['id'] ?>></textarea>
		</div>
