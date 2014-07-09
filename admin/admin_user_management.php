<?php
 if ( empty($in['seq']) )  return alert("잘못된 접근 입니다.");

 $result = $db->query("SELECT seq, username FROM admin WHERE seq=$in[seq]");
 $row = $result->fetch_assoc();
?>

<form method='post' target='hiframe' action='admin_user_management_submit.php'>
	<input type='hidden' name='seq' value='<?=$row['seq']?>' />
	<fieldset>
		<div>
			<span class='sub-title'>아이디</span> 
			<?=$row['username']?>
		</div>
		<div>
			<span class='sub-title'>비밀번호 변경</span>
			<input type='password' name='password' />
		</div>
		<div>
			<span class='sub-title'>비밀번호 확인</span>
			<input type='password' name='confirm_password' />
		</div>
		<input type='submit' value='변경하기' />
	</fieldset>
</form>