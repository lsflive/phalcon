<form action="" method="get">
<table class="table_add">
	<tr>
		<td class="tright" width="90">FID:</td>
		<td>
			<input type="text" name="fid" class="input" style="width: 30%;" value="{{this.request.getQuery('fid')}}" />
		</td>
	</tr>
	<tr>
		<td class="tright">标题:</td>
		<td>
			<input type="text" name="title" class="input" style="width: 80%;" value="{{this.request.getQuery('title')}}" />
		</td>
	</tr>
	<tr>
		<td class="tright">控制器:</td>
		<td>
			<input type="text" name="url" class="input" style="width: 80%;" value="{{this.request.getQuery('url')}}" />
		</td>
	</tr>
	<tr>
		<td class="tright">权限:</td>
		<td>
			<input type="text" name="perm" class="input" style="width: 50%;" value="{{this.request.getQuery('perm')}}" />
		</td>
	</tr>
	<tr>
		<td class="tright">图标样式:</td>
		<td>
			<input type="text" name="ico" class="input" style="width: 50%;" value="{{this.request.getQuery('ico')}}" />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td class="sub">
			<label class="webmis_bottom">搜索<input type="submit" name="search" value="" class="noDisplay" /></label>
		</td>
	</tr>
</table>
</form>