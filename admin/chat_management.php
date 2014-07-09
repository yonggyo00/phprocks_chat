<?php
include_once 'init.php';
	
if ( empty($_member) ) {
	echo "<h3>로그인을 해 주세요.</h3>";
}
else {
	if ( $in['option'] == 'admin_management' ) {
		include_once 'admin_management.php';
	}
	else if ( $in['option'] == 'admin_user_management' ) {
		include_once 'admin_user_management.php';
	}
	else {
		include_once 'search_form.php';
		
		?>
		<div id='total-result'>총 검색 결과 <b><?=number_format($total_post)?></b>개</div>
			<form id='chat-result-form' method='post' target='hiframe' action='chat_log_delete.php'>
				<table id='chat-log-table' cellpadding=0 cellspacing=0 width='100%' border=0>
					<tr id='tr-header'>
						<td nowrap>번호</td>
						<td nowrap>날짜</td>
						<td nowrap>방이름</td>
						<td nowrap>보낸이(아이디)</td>
						<td nowrap>보낸이(닉네임)</td>
						<td nowrap>귓속말</td>
						<td nowrap>귀속말(아이디)</td>
						<td nowrap>귓속말(닉네임)</td>
						<td nowrap width=500>메세지</td>
					</tr>
			<?php
				foreach ( $rows as $row ) {
					$mode = null;
					if ( $row['mode'] ) $mode = "귓속말";
					
					$checkbox = "<input type='checkbox' name='seq[]' value='$row[seq]' />";
					
			?>
					<tr>
						<td nowrap><?=$checkbox?><?=$row['seq']?></td>
						<td nowrap><?=date('Y-m-d H:i:s', $row['stamp'])?></td>
						<td nowrap><?=$row['roomname']?></td>
						<td nowrap><?=$row['from_user']?></td>
						<td nowrap><?=$row['from_nick']?></td>
						<td><?=$mode?></td>
						<td nowrap><?=$row['private_user']?></td>
						<td nowrap ><?=$row['private_nick']?></td>
						<td><?=$row['message']?></td>
					</tr>
			<?	}?>
					
				</table>
				<span id='select-all'>전체선택</span>
				<input type='submit' value='삭제하기' />
			</form>
		<?
		$option = array(
						'total_post' => $total_post,
						'no_of_post'=>  $no_of_post,
						'no_of_page' => 5
		);
		include_once 'paging.php';
	}
}
?>