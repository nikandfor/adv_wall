		<div class="bl_filter bl_filt_select <?= $params['class'] ?>" data-filtid=<?= $params['id'] ?>>
			<?php if(isset($params['label'])){ ?>
				<label for=<?= $params['id'] ?>><?= $params['label'] ?></label>
			<?php } ?>
				<ul>
				<?php
					foreach($params['vals'] as $id => $V):
				?>
						<li><input type=checkbox value=<?=$id?>><?=$V['val']?>
				<?php
					endforeach
				?>
				</ul>
		</div>
