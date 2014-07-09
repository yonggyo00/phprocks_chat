<?php
include_once 'init.php';
include_once 'functions.php';
?>

 <!DOCTYPE html>
	<html>
		<head>
			<meta charset='utf-8' />
		</head>
		<body>
	<?php
		 if ( empty($in['username']) || empty($in['password']) )  return alert("아이디와 비밀번호를 모두 입력하세요.");
		 
		 
		 // 로그인 정보를 확인 한다.
		 $result = $db->query("SELECT seq, username FROM admin WHERE username='$in[username]' AND password='".md5($in['password'])."'");
		 $row = $result->fetch_assoc();
		 $result->free();
		 
		 
		if ( $row['seq'] ) { // 로그인 정보가 있다면 쿠키에 값을 저장한다.
			$option = array(
							'seq' => $row['seq'],
							'username' => $row['username']
			);
			
			setcookie(md5('login_info'), base64_encode(serialize($option)), 0);
			
			echo "
					<script>
						parent.location.reload();
					</script>
			";
		}
		else return alert("로그인 정보가 정확하지 않습니다.");
		 
	?>
	</body>
 </html>