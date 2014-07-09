<?php
include_once 'admin_user_add.php';

$rows = db_rows("SELECT seq, username FROM admin ORDER BY seq DESC");
?>
<table id='chat-log-table' cellpadding=0 cellspacing=0 width='100%' border=0>
	<tr id='tr-header'>
		<td nowrap>번호</td>
		<td nowrap>아이디</td>
		<td>관리</td>
	</tr>
<?php
	foreach ( $rows as $row ) {
?>
	<tr>
		<td><?=$row['seq']?></td>
		<td><?=$row['username']?></td>
		<td>
			<a href='?option=admin_user_management&seq=<?=$row['seq']?>'>관리</a>
			<a href='admin_user_delete.php?seq=<?=$row['seq']?>' target='hiframe'>삭제</a>
		</td>
	</tr>
<?}?>	
</table>