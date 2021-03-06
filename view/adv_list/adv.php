<?php
$cell = function($t, $vals){
	$res = <<<EEE
		<span class=label>{$t['label']}</span>: 
		<span class=prop_val id=prop_val_{$t['prop']}>{$t['val']}</span>
EEE;
	$res .= "<select class=prop_edit_val name=prop_{$t['prop']} required>\n";
	foreach($vals as $v_id => $v_v)
		$res .= "<option value={$v_v['id']} ".($v_v['id'] == $t['val_id']? " selected": "").">{$v_v['val']}</option>\n";
	$res .= "</select>\n";

	return $res;
}
?>
<div class="a_item_container">
<div class="a_item a_item_<?= $params['view_type'] ?> <?= $params['addit_class'] ?>" data-itemid=<?= $params['id'] ?>>
	<form class=a_item_form name=a_edit_form_<?= $params['id']?> action="/printer.php?r=manageAdv" method=post enctype="multipart/form-data">
	<div class=wide_block>
		<div class="a_images img_uploader">
			<div class=a_ims_cont>
				<div class=a_ims_cont_pos>
			<?php
				foreach($params['ims'] as $im){
			?>
					<div class=a_ims_img data-imid=<?=$im['id']?>>
						<img src=<?=$im['src']?>>
					</div>
			<?php
				}
			?>
				</div>
			</div>
			<div class=a_ims_buttons>
				<input type=hidden name=image_act_1 value=add />
				<input type=hidden name=image_sort_1 value=100 />
				<input type=hidden name=image_fileid_1 value=image_upload_1 />
				<input type="file" name=image_upload_1 class=a_img_upload />
				<input type=hidden name=image_act_2 value=add />
				<input type=hidden name=image_sort_2 value=101 />
				<input type=hidden name=image_fileid_2 value=image_upload_2 />
				<input type="file" name=image_upload_2 class=a_img_upload />
			</div>
		</div>
		<div class="a_main_image">
			<?php
				if(isset($params['ims'][0])){
					$im = $params['ims'][0];
			?>
					<img src=<?=$im['src']?> data-imgid=<?=$im['id']?>>
			<?php
				}
			?>
		</div>
					<div class=a_item_cont id=a_item_cont>
						<?= $params['contact']['name'] ?> <a href="mailto:<?= $params['contact']['email'] ?>"><?= $params['contact']['email'] ?></a>
					</div>
		<div class=a_data_table>
					<input type=hidden class=append_inp_id name=id value=<?= $params['id'] ?>>
					<input type=hidden class=append_inp_act name=act value=<?= $params['act'] ?>>
					<table class=a_item_data_tbl id=a_item_data_tbl>
						<tr>
							<td colspan=2><?= $params['name'] ?></td>
						</tr>
						<?php
							$t = $params['table'];
							$sz = count($t);
							for($i = 0; $i < $sz; $i += 2){
						?>
							<tr>
								<td class=a_t_left>
									<?= $cell($t[$i + 0], $params['props'][$t[$i + 0]['prop']]['vals']) ?>
								</td>
						<?php if($i + 1 < $sz){ ?>
								<td class=a_t_right>
									<?= $cell($t[$i + 1], $params['props'][$t[$i + 1]['prop']]['vals']) ?>
								</td>
						<?php }else{ ?>
								<td class=a_t_right></td>
						<?php }?>
							</tr>
						<?php
							}
						?>
					</table>
		</div>
		<div class="a_butt a_butt_full click_ab click_ab_full">
			show full
		</div>
	</div>
	<div class=wide_block>
		<div class=a_data_desc>
							<?= $params['description'] ?>
		</div>
	</div>
	<div class=wide_block>
		<div class=a_butts>
			<div class="a_butt a_butt_left click_ab click_ab_hide">
				minimaze
			</div>
			<div class="a_butt click_ab click_ab_compl">
				complain
			</div>
			<div class="a_butt click_ab click_ab_send">
				send to email
			</div>
			<div class="a_butt click_ab click_ab_rm">
				удалячить
			</div>
			<div class="a_butt click_ab click_ab_edit">
				поредачить
			</div>
			<div class="a_butt click_ab click_ab_save">
				сохранить
			</div>
			<div class="a_butt a_butt_submit">
				<input type=submit value="submit">
			</div>
		</div>
	</div>
	</form>
</div>
</div>
