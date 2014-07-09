<div id='site-top'>
<?php
	if ( $_member ) {?>
		<span style='float: left;'>
			<a href='?'>홈</a>
			<a href='?option=admin_management'>관리</a>
		</span>
		<span style='float:right;'>
			<?=$_member['username']?>님 로그인
			<a href='logout.php' target='hiframe' >로그아웃</a>
		</span>
		<div style='clear:both;'></div>
<?} else {?>
	<form method='post' target='hiframe' action='login_submit.php' autocomplete='off'>
		아이디 <input style='padding: 2px 10px;' type='text' name='username' placeholder='아이디' />
		비밀번호 <input style='padding: 2px 10px;' type='password' name='password' placeholder='비밀번호' />
		<input type='submit' value='로그인' />
	</form>
<? }?>
</div>