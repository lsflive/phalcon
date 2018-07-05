<form action="{{base_url}}SysMenus/editData" method="post" id="Form">
<table class="table_add">
	<tr>
		<td class="tright" width="90"></td>
		<td id="textVal"><span class="c2">请认真填写以下表单！</span></td>
	</tr>
	<tr>
		<td class="tright">FID:</td>
		<td>
			<input type="text" id="menus_fid" name="fid" value="{{edit.fid}}" class="input" style="width: 60px;" />
			<div id="menusClass">&nbsp;</div>
		</td>
	</tr>
	<tr>
		<td class="tright">标题:</td>
		<td>
			<input type="text" name="title" value="{{edit.title}}" class="input" style="width: 70%;" rangelength="[2,12]" required />
		</td>
	</tr>
	<tr>
		<td class="tright">控制器:</td>
		<td>
			<input type="text" name="url" value="{{edit.url}}" class="input" style="width: 40%;" />
		</td>
	</tr>
	<tr>
		<td class="tright">权限:</td>
		<td id="PermVal">
<?php foreach($perm as $val){
	$checked = intval($edit->perm)&intval($val->perm)?'checked':'';
?>
			<input type="checkbox" class="Checkbox" value="{{val.perm}}" {{checked}}/><span class="inputText">{{val.name}}</span>
<?php }?>
			<input type="hidden" id="menusPerm" name="perm" value="{{edit.perm}}" />
		</td>
	</tr>
	<tr>
		<td class="tright">图标样式:</td>
		<td>
			<input type="text" name="ico" value="{{edit.ico}}" class="input" style="width: 40%;" />
		</td>
	</tr>
	<tr>
		<td class="tright">排序:</td>
		<td>
			<input type="text" name="sort" value="{{edit.sort}}" class="input" style="width: 30%;" />
		</td>
	</tr>
	<tr>
		<td class="tright">备注:</td>
		<td>
			<textarea name="remark" style="width: 90%; height: 60px;">{{edit.remark}}</textarea>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td class="sub">
			<label class="webmis_bottom">编辑<input type="submit" class="noDisplay" /></label>
			<input type="hidden" name="id" value="{{edit.id}}" />
		</td>
	</tr>
</table>
</form>