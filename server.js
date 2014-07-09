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

var mysql = require('mysql');
var connection = mysql.createConnection({
	host: '127.0.0.1',
	user : 'user',
	password : 'password'
});

connection.connect(); // 채팅 메세지는 계속 들어오기 때문에 연결을 열어 놓는다.

connection.query("USE chat");


var io = require('socket.io')(3000);
var chat = io.of('/chat');

var room_data = [];
var users = [];


chat.on('connection', function(socket) {
	
	socket.on('join_room', function(data) {
		socket.join(data.roomname);
		
		// 필수 데이터 저장
		room_data.push({"id" : socket.id, "room" : data.roomname });
			
		// 사용자 정보
		users.push({"id" : socket.id, "username" : data.username, "nickname" : data.nickname, "roomname": data.roomname });
		
		// 사용자 정보 전달
		var room_users = [];
		for(var i = 0; i < users.length; i++ ) {
			if ( users[i].roomname == data.roomname ) {
				room_users.push(users[i]);
			}
		}
		var roomname = get_room_name(socket.id, room_data);
		chat.in(roomname).emit('user_join', JSON.stringify(room_users));
		
		// 방 정보 전달
		chat.emit('room_list',  get_room_list(io.nsps['/chat'].adapter.rooms));
		
		
		// 기존의 대화 내용 15라인 정도 가져오고 새로 접속한 회원에게 전달 한다.
		connection.query("SELECT * FROM chat_log WHERE roomname = "+connection.escape(roomname)+" ORDER BY stamp DESC LIMIT 15", function(err, results, fields) {
			if (err)
				throw err;
			
			chat.in(roomname).emit('initial_chat_log', JSON.stringify(results));
		});
		
	});
	
	socket.on('request_room_list', function(data) {
		chat.emit('room_list',  JSON.stringify(get_room_list(io.nsps['/chat'].adapter.rooms)));
	});
	
	socket.on('message', function(da) {
		console.log("incoming chat-log", da);
		var data = JSON.parse(da);
		
		var roomname = get_room_name(socket.id, room_data);
		chat.in(roomname).emit('message', da);
		
		// 채팅 메세지는 데이터 베이스에 저장한다.
		var stamp = Math.round((new Date()).getTime() / 1000);
		var room_name = connection.escape(roomname);
		var from_user = connection.escape(data.from_user);
		var from_nick = connection.escape(data.from_nick);
		var message = connection.escape(data.message);
		var chat_mode = connection.escape(data.mode);
		var private_user = connection.escape(data.private_user);
		var private_nick = connection.escape(data.private_nick);

		
		var query = "INSERT INTO chat_log (stamp, roomname, from_user, from_nick, private_user, private_nick, mode, message) VALUES ("+stamp+","+room_name + "," +from_user+", "+from_nick+ ","+ private_user + "," + private_nick + ","+ chat_mode+ "," + message+")";
		
		connection.query(query);
		
	});
	
	// 접속이 끊겼을 경우
	socket.on('disconnect', function() {
		var roomname = get_room_name(socket.id, room_data);
		
		// 접속한 사용자의 방 정보를 삭제
		for(var i=0; i < room_data.length; i++ ) {
			if ( room_data[i].id == socket.id ) {
				room_data.splice(i, 1);
			}
		}
		
		// 접속한 사용자를 삭제한다.
		for(var i = 0; i < users.length; i++ ) {
			if ( users[i].id == socket.id ) {
				users.splice(i, 1);
			}
		}
		
		// 방정보를 업데이트 한다. 
		var room_list = get_room_list(io.nsps['/chat'].adapter.rooms);
		chat.emit('room_list', JSON.stringify(room_list));
		
		// 사용자 정보를 업데이트 한다.
		// 사용자 정보 전달
		var room_users = [];
		for(var i = 0; i < users.length; i++ ) {
			if ( users[i].roomname == roomname ) {
				room_users.push(users[i]);
			}
		}
	
		chat.in(roomname).emit('user_list', JSON.stringify(room_users));
	});
});

function get_room_list(room_list) {
	
	var rooms = [];
	var room_pattern = /^room_(\S+)/;
	for( key in room_list ) {
		var match = room_pattern.exec(key);
			for( k in match ) {
				if ( k == 1 ) {
					
					var count = 0;
					for( r in room_list[key] ) {
						count++;
					}
					if ( count > 0 ) {
						rooms.push({"roomname" : match[k], "no_of_person" : count });
					}
				}
			}
	}
	
	return rooms;
}

function get_room_name( id, data ) {
	for ( var i= 0; i < data.length; i++ ) {
		if ( data[i].id == id ) {
			return data[i].room;
			break;
		}
	}
}