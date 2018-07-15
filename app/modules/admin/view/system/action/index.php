<span class="action">
	<span class="title"><?php echo $Menus['Ctitle'];?></span>
	<div class="actionM">
		<div id="Menus">
<?php
foreach ($Menus['action'] as $val){
	$url = $val['ico']=='ico-list'?$this->url->get($this->dispatcher->getControllerName()):'';
?>
			<a href="<?php echo $url;?>" id="<?php echo $val['ico'];?>"><em class="<?php echo $val['ico'];?>"></em><span><?php echo $val['name'];?></span></a>
<?php }?>
		</div>
	</div>
</span>
<table class="table_list">
	<tr class="title">
		<td width="20"><a href="#" id="checkboxY"></a><a href="#" id="checkboxN"></a></td>
		<td width="60">ID</td>
		<td width="80">名称</td>
		<td width="80">权限值</td>
		<td>图标</td>
	</tr>
	<tbody id="listBG">
<?php foreach($Page->items as $val){ ?>
	<tr>
		<td id="Checkbox"><input type="checkbox" value="<?php echo $val->id;?>" /></td>
		<td><?php echo $this->inc->keyHH($val->id,$this->request->getQuery('id'));?></td>
		<td><b><?php echo $this->inc->keyHH($val->name,$this->request->getQuery('name'));?></b></td>
		<td><?php echo $val->perm;?></td>
		<td class="tleft"><?php echo $val->ico;?></td>
	</tr>
<?php }?>
	</tbody>
</table>
<div class="page"><?php echo $Page->PageHtml;?></div>