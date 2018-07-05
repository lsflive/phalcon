<span class="action">
	<span class="title">{{Menus['Ctitle']}}</span>
	<div class="actionM">
		<div id="Menus">
<?php
foreach ($Menus['action'] as $val){
	$url = $val['ico']=='ico-list'?$base_url.$this->dispatcher->getControllerName():'';
?>
			<a href="{{url}}" id="{{val['ico']}}"><em class="{{val['ico']}}"></em><span>{{val['name']}}</span></a>
<?php }?>
		</div>
	</div>
</span>
<table class="table_list">
	<tr class="title">
		<td width="20"><a href="#" id="checkboxY"></a><a href="#" id="checkboxN"></a></td>
		<td width="60">ID</td>
		<td>FID</td>
		<td>名称</td>
		<td>控制器</td>
		<td >权限值</td>
		<td>图标</td>
		<td>创建时间</td>
		<td>排序</td>
		<td>备注</td>
	</tr>
	<tbody id="listBG">
<?php foreach($Page->items as $val){ ?>
	<tr>
		<td id="Checkbox"><input type="checkbox" value="{{val.id}}" /></td>
		<td>{{val.id}}</td>
		<td>{{val.fid}}</td>
		<td><b><?php echo $this->inc->keyHH($val->title,@$_GET['title']);?></b></td>
		<td>{{val.url}}</td>
		<td>{{val.perm}}</td>
		<td>{{val.ico}}</td>
		<td>{{val.ctime}}</td>
		<td>{{val.sort}}</td>
		<td class="tleft">{{val.remark}}</td>
	</tr>
<?php }?>
	</tbody>
</table>
<div class="page">{{Page.PageHtml}}</div>