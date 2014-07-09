<?php
include_once 'init.php';
if ( $in['username'] && $in['password'] && $in['confirm_password'] ) {
	if ( $in['password'] != $in['confirm_password'] ) return alert("비밀번호를 정확하게 입력하세요.");
	
	
	if ( strlen($in['username']) < 4) return alert("아이디는 4자 이상 입력하세요.");
	if ( strlen($in['password']) < 4 ) return alert("비밀번호는 4자 이상 입력하세요.");
	
	// 회원이 존재하는지 확인 한다.
	$result = $db->query("SELECT COUNT(*) as cnt FROM admin WHERE username='".$in['username']."'");
	$row = $result->fetch_assoc();
	$result->free();
	
	if ( $row['cnt'] ) return alert("이미 존재하는 아이디 입니다.");
	else {
		$query = "INSERT INTO admin (username, password) VALUES ('".$in['username']."', '".md5($in['password'])."')";
		if ( $db->query($query) ) {
			echo "
				<script>
					parent.location.reload();
				</script>
			";
			
			
		}
	}
}
else return alert("아이디, 비밀번호, 비밀번호 확인을 모두 입력하세요.");
?>