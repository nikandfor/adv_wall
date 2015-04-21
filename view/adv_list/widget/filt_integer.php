		<div class="bl_filter bl_filt_range <?= $params['class'] ?>" id=<?=$params['id']?>>
			<?php if(isset($params['label'])){ ?>
				<label><?= $params['label'] ?>
			<?php } ?>
				<input class="range_inp_min" type=number>
				<input class="range_inp_max" type=number>
			<?php if(isset($params['label'])){ ?>
				</label>
			<?php } ?>
		</div>
