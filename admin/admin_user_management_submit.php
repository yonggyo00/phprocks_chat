<?php
include_once 'init.php';
if ( empty($in['seq']) ) return alert("잘못된 접근 입니다.");

if ( empty($in['password']) || empty($in['confirm_password']) ) return alert("비밀번호를 모두 입력하세요.");

if ( $in['password'] != $in['confirm_password'] ) return alert("비밀번호를 정확하게 입력하세요.");
else {
	if (strlen($in['password']) < 4 ) return alert("비밀번호는 4자리 이상 입력해 주세요.");
	else {
		 if (  $db->query("UPDATE admin SET password='".md5($in['password'])."' WHERE seq={$in[seq]}") ) {
			echo "
				<script>
					alert('변경 되었습니다.');
					parent.location.reload();
				</script>
			";
			
		 }
		
		
	}
}
?>