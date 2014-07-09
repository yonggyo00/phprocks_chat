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
 
$in = array_merge($_GET, $_POST);

if ( empty($in['username']) ) $in['username'] = uniqid();
if ( empty($in['nickname']) ) $in['nickname'] = '손님'; 
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<link rel='stylesheet' type='text/css' href='css/chat.css' />
		<script src='js/jquery.js'></script>
		<script src='http://localhost:3000/socket.io/socket.io.js'></script>
		<script>
		var chat; 
		$(document).ready(function() {
			chat = io.connect('http://localhost:3000/chat');
			// 방목록 데이터
			chat.emit('request_room_list');
			chat.on('room_list', function(da) {
				var data = JSON.parse(da);
				
				$("#room-list").html(room_list(data));
				
			});
		});
		
		function room_list( data ) {
			var room_info = "<table border=0 cellpadding=0 cellspacing=0 width='100%'>"+
								"<tr id='tr_header'>" +
								"<td nowrap align='left'>방이름</td>" + 
								"<td nowrap align='right'>참여자수</td>" + 
								"</tr>";
			for(var i=0; i < data.length; i++ ) {
				room_info += "<tr>"+
								"<td width='85%' align='left'>" + 
									"<a href='chat_room.php?room="+data[i].roomname+"&username=<?=$in['username']?>&nickname=<?=$in['nickname']?>'>"+
								data[i].roomname+"</a>"+
								"</td>" +
								"<td width='15%' align='right'>" +
									data[i].no_of_person+
								"</td>";
							"</tr>";
			}
			room_info += "</table>";
	
			return room_info;
		}
		</script>
	</head>
	<body>
		<div id='chat-lobby'>
			<div id='room-list'></div>
			<form method='get' action='chat_room.php' autocomplete='off'>
				<input type='hidden' name='username' value='<?=$in['username']?>' />
				<input type='hidden' name='nickname' value='<?=$in['nickname']?>' />
				
				<div id='create-room'>
					<input type='text' name='room' placeholder='생성할 방 이름 입력' />
					<input type='submit' value='방만들기'/>
				</div>
			</form>
		</div>
	</body>
</html>