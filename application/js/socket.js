var app = require('http').createServer(handler)
  , io = require('socket.io').listen(app)
  , fs = require('fs')
  , mysql = require('mysql');

var conn = mysql.createConnection({
	host: "localhost",
	port: "3306",
	user: "root",
	password: "123qwe",
	database: "bbabbabba"
});

conn.connect(function(err){
	if(err){
		console.log("mysql연결에 실패");
		return;
	}
});

 app.listen(8005);

function handler(req, res){

	fs.readFile(__dirname +'/ppt_1.html',
		function (err, data){
		if(err)
		{
			res.writeHead(500);
			return res.end('Error loading index.html');
		}

		res.writeHead(200);
		res.end(data);
	});
}

io.sockets.on('connection', function(socket) {
	this.roomNum = 0;

	socket.on('setRoom', function(roomNum){
		socket.join(roomNum);
		this.roomNum = roomNum;
	});

	socket.on('sendMessage', function(data){
		socket.emit("my_message", data); //나한테
		socket.broadcast.to(this.roomNum).emit("message", data); //모두에게
	});

	socket.on('modifyBox', function(data){
		console.log(data.box_idx);
		conn.query('update box set ? where box_idx = ? and canvas_idx = ? and project_idx = ?', [{
				box_x : data.box_x
				, box_y : data.box_y
				, box_w : data.box_w
				, box_h : data.box_h
				, box_sx : data.box_sx
				, box_sy : data.box_sy
				, box_ex : data.box_ex
				, box_ey : data.box_ey
				, box_alpha : data.box_alpha
				, box_fill_color : data.box_fill_color
				, box_stroke_color : data.box_stroke_color
				, box_line_width : data.box_line_width
				, box_line_cap : data.box_line_cap
				, box_text : data.box_text
			}, data.box_idx, data.canvas_idx, data.project_idx], function(err, res, field){
			if(err){
				console.log("에러 발생."); return;
			}
		});
		
		console.log("roomNum : "+this.roomNum);
		socket.broadcast.to(this.roomNum).emit("modifyBox", data);
	});

	socket.on('addBox', function(data){
		conn.query('insert into box set ?', data, function(err, res, field){
			if(err){
				console.log("에러 발생."); return;
			}
		});
		socket.broadcast.to(this.roomNum).emit("addBoxToParent", data);
	});

	socket.on('delBox', function(data){
		conn.query('delete from box where box_idx = ? and canvas_idx = ? and project_idx = ?', [data.box_idx, data.canvas_idx, data.project_idx], function(err, res, field){
			if(err){
				console.log("에러 발생."); return;
			}
		});
		socket.broadcast.to(this.roomNum).emit("delBox", data);
	});

	socket.on('addCanvas', function(data){
		var max_ord = 0;
		conn.query('select max(canvas_ord) as ord from canvas where project_idx = ?', data.project_idx, function(err, res, field){
			if(res.length > 0){
				max_ord = res[0].ord+1;
			}
		});

		var canvas = { canvas_idx : data.canvas_idx, project_idx : data.project_idx, canvas_bg : data.canvas_bg, canvas_ord : max_ord }
		conn.query('insert into canvas set ?', canvas, function(err, res, field){
			if(err){
				console.log("에러 발생."); return;
			}
		});
		socket.broadcast.to(this.roomNum).emit("addCanvas", data);
	});
});