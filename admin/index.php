<?php
/************************************************************
 *                                                          *
 * PHPROCKS.COM 채팅 서버, 클라이언트  Version 0.2          *
 * Copyright(C) 2014, 만든이 이용교(LEE, YONGGYO)           *
 * 이 소스는 GPL 라이센스로 배포됩니다.                     *
 * 수정 및 상업적인 용도로 사용하셔도 무방하오나,           *
 * 출처 및 만든이 관련 주석은 제거하지 마십시오.            *
 * 만든이 이메일 - lyonggyo@gmail.com                       *
 *                                                          *
 * 사용법                                                   *
 * 아래의 주소를 참고 하세요.                               *
 *  http://phprocks.com/?module=post&action=view&seq=428    *
 *                                                          *
 *                                                          *
 ************************************************************
 */
 
	include_once 'init.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<script src='../js/jquery.js'></script>
		<link rel='stylesheet' type='text/css' href='../js/jquery-ui.min.css' />
		<script src='../js/jquery-ui.min.js'></script>
		<link rel='stylesheet' type='text/css' href='css/admin.css' />
		<script src='js/admin.js'></script>
	</head>
	<body>
	<?php
		 include_once 'login.php';
		 if ( $_member ) include_once 'chat_management.php';
		 else include_once 'intro.php';
	?>
		<iframe name='hiframe' style='width: 100%; height: 200px; display: none;'></iframe>
	</body>
</html>