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
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8' />
		<link rel='stylesheet' type='text/css' href='css/chat.css' />
		<script src='js/jquery.js'></script>
		<script src='http://localhost:3000/socket.io/socket.io.js'></script>
<?php
$in = array_merge($_GET, $_POST);
$in['room'] = stripslashes(preg_replace('/[^a-zA-Z0-9\xE0-\xFF\x80-\xFF\x80-\xFF]/s', '', $in['room']));

	if ( empty($in['room']) ) {
		echo "
			<script>
				alert('방 이름을 입력하세요');
				window.location.back();
			</script>
		";
		return;
	}
	if ( empty($in['username']) || empty($in['nickname']) ) {
		echo "
			<script>
				alert('사용자 계정이 전달되지 않았습니다.');
				window.location.back();
			</script>
		";
		
		return;
	}
?>

		<script>
		/*
		 * mode = 1; 귓속말
		 * private_user = "username"; 귓속말 모드인 경우는 username이 포함된다.
		*/
		
		var chat;
		var mode = 0;  
		var private_user = "";
		var private_nick = "";
		
		$(document).ready(function() {
			chat = io.connect('http://localhost:3000/chat');
			
			chat.emit('join_room', { roomname : "room_<?=$in['room']?>", username : "<?=$in['username']?>", nickname : "<?=$in['nickname']?>"});
			
			
			// 기존 대화 내용 업데이트
			// 한번 렌더 된 것은 다시 렌더 하지 않는데.
			var is_rendered = false; 
			
			if ( is_rendered == false ) {
				chat.on('initial_chat_log', function(data) {
					var chat_log = JSON.parse(data);
					
					for( var i = chat_log.length - 1; i >= 0; i-- ) {
						if ( chat_log[i].mode == 1 ) { // 귓속말 모드
							if ( chat_log[i].private_user == "<?=$in['username']?>" ) {
								$("#chat_view").append("<div class='row private'>"+"<b>[귓속말 "+chat_log[i].from_nick+"("+chat_log[i].from_user+")]</b>"+chat_log[i].message+"</div>");
							}
							else if ( chat_log[i].from_user == "<?=$in['username']?>" ) {
								$("#chat_view").append("<div class='row private'><b>"+chat_log[i].from_nick + "("+chat_log[i].from_user+")[귓속말]</b>"+chat_log[i].message+"</div>");
							}
						}
						else {
							$("#chat_view").append("<div class='row'><b>"+chat_log[i].from_nick + "("+chat_log[i].from_user+")</b>"+ " "+chat_log[i].message+"</div>");
						}
					}
					is_rendered = true;
				});
			}
			
			// 방 참여시 사용자 정보 업데이트
			chat.on('user_join', function(data) {
				$("#user-list").html(user_list(JSON.parse(data)));
			});
			
			// 방 참여시 인사말 전달.
			send_message("님 입장하셨습니다.");
			
			// 사용자 정보 데이터
			chat.on('user_list', function(da) {
				var data = JSON.parse(da);
				
				$("#user-list").html(user_list(data));
			});
			
			// 메세지가 도달했을 때
			chat.on('message', function(da) {
				var data = JSON.parse(da);
				
				var sender_info = data.from_nick + "("+data.from_user+")";
				if ( data.mode == 1 ) { // 귓속말 모드
				    if ( data.private_user == "<?=$in['username']?>" ) {
						$("#chat_view").append("<div class='row private'>"+"<b>[귓속말 "+data.from_nick+"("+data.from_user+")]</b>"+data.message+"</div>");
					}
				}
				else {
					$("#chat_view").append("<div class='row'><b>"+sender_info+"</b> "+data.message+"</div>");
				}
				
				// 메세지가 붙여진 후 스크롤다운 시킨다.
				$("#chat_view").scrollTop($("#chat_view")[0].scrollHeight);
			});
			
			// 메세지 전달
			$("#chat-message").keypress(function(e) {
				if ( e.keyCode == 13 ) {
					var message = $(this).val();
					$(this).val('');
					
					send_message(message);
				}
			});
			
			// 귓속말 
			$("body").on("click", "#user-list > .row", function() {
				
				private_user = $(this).attr('username');
				private_nick = $(this).attr('nickname');
				
				// 귓속말을 보낼 사용자가 본인인 경우는 귓속말을 보내지 않는다.
				if ( private_user != "<?=$in['username']?>" ) {
					mode = 1;
					
					$("#chat-mode-box > #chat-mode").html(private_nick+"("+private_user+")님에게 <b>귓속말</b>..<span id='cancel-private-msg'>취소</span>");
				}
			});
			
			// 귓속말 취소
			$("body").on("click", "#chat-mode-box > #chat-mode > #cancel-private-msg", function() {
				$("#chat-mode-box > #chat-mode").html("<b>귓속말</b>은 왼쪽사용자 목록에서 사용자를 클릭 하세요.");
				mode = 0;
				private_user = "";
				private_nick = "";
			});
			
			// 전체 사용자에게 메세지 보내기
			$("#whole-user").click(function() {
				mode = 0;
				private_user = "";
				private_nick = "";
				$("#chat-mode-box > #chat-mode").html("<b>귓속말</b>은 왼쪽사용자 목록에서 사용자를 클릭 하세요.");
			});
		});
		
		function send_message( msg ) {
			chat.emit('message', JSON.stringify({"message" : strip_tags(msg), "mode" : mode, 'private_user' : private_user, "private_nick" : private_nick, "from_user" : "<?=$in['username']?>", "from_nick" : "<?=$in['nickname']?>" }) );
			
			// 자신이 보낸 귓속말인 경우는
			if ( mode == 1 ) {
				$("#chat_view").append("<div class='row private'><b><?=$in['nickname']?>(<?=$in['username']?>)[귓속말]</b>"+msg+"</div>");
			}	
		}
		
		function user_list ( data ) {
			var user_info = "";
			var my_account = 0;
			for( var i = 0; i < data.length; i++ ) {
				
				if ( data[i].username.indexOf("<?=$in['username']?>") > -1 ) {
					if ( my_account > 0 ) continue;
					else {
						user_info += "<div class='row' username='"+data[i].username+"' nickname='"+data[i].nickname+"'>"+data[i].nickname+"("+data[i].username+")</div>";
					}
					my_account++;
				}
				else {
					user_info += "<div class='row' username='"+data[i].username+"' nickname='"+data[i].nickname+"'>"+data[i].nickname+"("+data[i].username+")</div>";
				}
			}
			
			return user_info;
		}
		
		function strip_tags(html){
 
			//PROCESS STRING
			if(arguments.length < 3) {
				html=html.replace(/<\/?(?!\!)[^>]*>/gi, '');
			} else {
				var allowed = arguments[1];
				var specified = eval("["+arguments[2]+"]");
				if(allowed){
					var regex='</?(?!(' + specified.join('|') + '))\b[^>]*>';
					html=html.replace(new RegExp(regex, 'gi'), '');
				} else{
					var regex='</?(' + specified.join('|') + ')\b[^>]*>';
					html=html.replace(new RegExp(regex, 'gi'), '');
				}
			}
	 
			//CHANGE NAME TO CLEAN JUST BECAUSE 
			var clean_string = html;
	 
			//RETURN THE CLEAN STRING
			return clean_string;
		}
	</script>
	</head>
	<body>

		<div id='chat'>
			<div id='title'><span>방이름</span> <?=$in['room']?></div>
			<div id='chat-view-box'>
				<div id='chat_view'></div>
			</div>	
			<div id='right'>
				<div id='user-info'>
					<div id='whole-user'>전체사용자</div>
					<div id='user-list'></div>
				</div>
			</div>
			<div style='clear:left;'></div>
			
			<div id='chat-message-box'>
				<input type='text' id='chat-message' placeholder='전달할 메세지를 입력하세요.' />
			</div>
			<div id='chat-mode-box'>
				<span id='chat-mode'><b>귓속말</b>은 왼쪽사용자 목록에서 사용자를 클릭 하세요.</span>
				
				<a id='leave-button' href='chat.php?username=<?=$in['username']?>&nickname=<?=$in['nickname']?>'>방나가기</a>
				<div style='clear: both;'></div>
			</div>
		</div>
	</body>
</html>