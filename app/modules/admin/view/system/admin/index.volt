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
		<td width="80">用户名</td>
		<td>邮箱</td>
		<td>手机号码</td>
		<td>部门</td>
		<td>职位</td>
		<td>姓名</td>
		<td width="120">注册时间</td>
		<td width="40">状态</td>
		<td width="40">权限</td>
	</tr>
	<tbody id="listBG">
<?php foreach($Page->items as $val){ ?>
	<tr>
		<td id="Checkbox"><input type="checkbox" value="{{val.id}}" /></td>
		<td>{{val.id}}</td>
		<td><b>{{val.uname}}</b></td>
		<td>{{val.email}}</td>
		<td>{{val.tel}}</td>
		<td>{{val.department}}</td>
		<td>{{val.position}}</td>
		<td><?php echo $this->inc->keyHH($val->name,@$_GET['name']);?></td>
		<td>{{val.rtime}}</td>
		<td>{{val.state=='1'?'<span class="green">正常</span>':'<span class="red">禁用</span>'}}</td>
		<td><a href="" title="{{val.perm}}" onclick="editPerm('{{val.id}}','{{val.perm}}');return false;">编辑</a></td>
	</tr>
<?php }?>
	</tbody>
</table>
<div class="page">{{Page.PageHtml}}</div>