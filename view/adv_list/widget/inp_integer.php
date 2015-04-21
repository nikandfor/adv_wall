		<div class="bl_inp bl_inp_<?= $params['type'] ?> <?= $params['class'] ?>">
			<?php if(isset($params['label'])){ ?>
				<label><?= $params['label'] ?>
			<?php } ?>
				<input id=<?=$params['id']?> type=number>
			<?php if(isset($params['label'])){ ?>
				</label>
			<?php } ?>
		</div>
