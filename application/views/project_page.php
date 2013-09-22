




<!DOCTYPE html>
<html lang="kor">
<head>
	<meta charset="utf-8">
	<title>Project List</title>

<style type="text/css">
	html { height:100%; margin:0; padding:0; }
	body { height:100%; margin:0; padding:0; }
</style>
<link href="css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
<link href="application/js/bootstrap-2.3.2/docs/css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet"/>
<link href="application/js/bootstrap-2.3.2/docs/assets/css/bootstrap.css" rel="stylesheet">

<style type="text/css">
	body { padding-top: 40px; padding-bottom: 40px; }
	.sidebar-nav { padding: 9px 0; }

	@media (max-width: 980px) {
		/* Enable use of floated navbar text */
		.navbar-text.pull-right {
			float: none;
			padding-left: 5px;
			padding-right: 5px;
			}
		}
</style>
<link rel="Stylesheet" type="text/css" href="application/js/bubble.css" />

<script src="application/js/common/jquery-2.0.2.min.js"></script>
<script src="application/js/shapeList.js"></script>
<script src="application/js/shape.js"></script>
<script src="application/js/canvas.js"></script>
<link href="application/js/bootstrap-2.3.2/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">

<script src="http://203.253.20.235:8005/socket.io/socket.io.js"></script>


<!-- 부트스트랩 -->
<link href="application/js/bootstrap-2.3.2/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">
<link href="application/js/bootstrap-2.3.2/docs/css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet"/>
<link href="application/js/bootstrap-2.3.2/docs/assets/css/bootstrap.css" rel="stylesheet">
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-transition.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-alert.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-modal.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-dropdown.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-scrollspy.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-tab.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-tooltip.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-popover.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-button.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-collapse.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-carousel.js"></script>
<script src="application/js/bootstrap-2.3.2/docs/assets/js/bootstrap-typeahead.js"></script>

<script>

$(window).resize(function(){
	wWidth = $(window).width();
	wHeight = $(window).height();
	$("#rightTab").width(wWidth-320);
	$("#mainViewFrame").width(wWidth-320);
	$("#mainViewFrame").height(wHeight);
});

var canvases = [];
var project_idx = "<?=$this->session->userdata('project_idx');?>";
var user_idx = "<?=$this->session->userdata('user_idx');?>";
var user_name = "<?=$this->session->userdata('user_name');?>";
var canvasList = eval("("+'<?=$canvasList;?>'+")");
var nowC;

function preLoad(){
	for(i=0; i<canvasList.length; i++){
		var canvasNum = canvasList[i]['canvas_idx'];
		var makeCanvas = $("<canvas id='canvas"+canvasNum+"' canvasNum='"+canvasNum+"' width='640' height='480' style='border:1px #ddd solid;width:280px;height:200px;' onclick='loadCanvas("+canvasNum+");'>This text is displayed if your browser does not support HTML5 Canvas.</canvas>");
		makeCanvas.appendTo("#canvasView");

		var tempC = new canvas();
		tempC.background = canvasList[i]['bg'];
		tempC.ctx = makeCanvas.get(0).getContext("2d");

		var boxList = canvasList[i].boxList;
		for(j=0; j<boxList.length; j++){
			var tempS = new shape();
			tempS.name = boxList[j]["box_name"];
			tempS.x = parseInt(boxList[j]["box_x"]);
			tempS.y = parseInt(boxList[j]["box_y"]);
			tempS.w = parseInt(boxList[j]["box_w"]);
			tempS.h = parseInt(boxList[j]["box_h"]);
			tempS.sx = parseInt(boxList[j]["box_sx"]);
			tempS.sy = parseInt(boxList[j]["box_sy"]);
			tempS.ex = parseInt(boxList[j]["box_ex"]);
			tempS.ey = parseInt(boxList[j]["box_ey"]);
			tempS.text = boxList[j]["box_text"];
			tempS.alpha = boxList[j]["box_alpha"];
			tempS.fillColor = boxList[j]["box_fill_color"];
			tempS.strokeColor = boxList[j]["box_stroke_color"];
			tempS.lineWidth = boxList[j]["box_line_width"];
			tempS.lineCap = boxList[j]["box_line_cap"];
			tempC.boxes[boxList[j]["box_idx"]] = tempS;
		}
		canvases[canvasNum] = tempC;
		canvases[canvasNum].draw();
	}
	wWidth = $(window).width();
	wHeight = $(window).height();
	$("#rightTab").width(wWidth-320);
	$("#mainViewFrame").width(wWidth-320);
	$("#mainViewFrame").height(wHeight-30);

	$("#mainViewFrame").load(function(){
		//if(mainViewFrame.parentC==null){
			if(canvases.length==0){
				addCanvas();
			}
			loadCanvas(0);
		//}
	});
}

function addCanvas(){
	var canvasNum = canvases.length;
	var makeCanvas = $("<canvas id='canvas"+canvasNum+"' canvasNum='"+canvasNum+"' width='640' height='480' style='border:1px #ddd solid;width:280px;height:200px;' onclick='loadCanvas("+canvasNum+");'>This text is displayed if your browser does not support HTML5 Canvas.</canvas>");
	makeCanvas.appendTo("#canvasView");

	var c = new canvas();
	c.ctx = makeCanvas.get(0).getContext("2d");

	canvases[canvasNum] = c;
	socket.emit("addCanvas", {canvas_idx : canvasNum, project_idx : project_idx, canvas_bg : c.background});
}

function loadCanvas(num){
	nowC = canvases[num];
	mainViewFrame.loadCanvas(num);
}

</script>



<script>
/*socket*/

var roomName = 1;
var chatStory = [];
var pop=0;
var push=0;

var socket = io.connect("http://203.253.20.235:8005");

socket.on("message", function (data) {
	message_list.innerHTML = message_list.innerHTML + data.user_name+" :" + data.message+"<br />"  ;
	pushChat();
	overal();
});

socket.on("my_message", function (data) {
	message_list.innerHTML = message_list.innerHTML + data.user_name+" :" + data.message +"<br />" ;
	overal();
});

socket.on("modifyBox", function (data){
	tempC = canvases[data.canvas_idx];

	var tempS = tempC.boxes[data.box_idx];
	tempS.x = data.box_x;
	tempS.y = data.box_y;
	tempS.w = data.box_w;
	tempS.h = data.box_h;
	tempS.sx = data.box_sx;
	tempS.sy = data.box_sy;
	tempS.ex = data.box_ex;
	tempS.ey = data.box_ey;
	tempS.text = data.box_text;
	tempS.alpha = data.box_alpha;
	tempS.fillColor = data.box_fill_color;
	tempS.strokeColor = data.box_stroke_color;
	tempS.lineWidth = data.box_line_width;
	tempS.lineCap = data.box_line_cap;

	mainViewFrame.clear(tempC.ctx);
	tempC.draw();
	if(mainViewFrame.parentC==tempC){
		loadCanvas(data.canvas_idx);
	}
});

socket.on("addBoxToParent", function (data) {
	tempC = canvases[data.canvas_idx];

	if(tempC.boxes.length!=data.box_idx){
		return;
	}

	var tempS = new shape();
	tempS.name = data.box_name;
	tempS.x = data.box_x;
	tempS.y = data.box_y;
	tempS.w = data.box_w;
	tempS.h = data.box_h;
	tempS.sx = data.box_sx;
	tempS.sy = data.box_sy;
	tempS.ex = data.box_ex;
	tempS.ey = data.box_ey;
	tempS.text = data.box_text;
	tempS.alpha = data.box_alpha;
	tempS.fillColor = data.box_fill_color;
	tempS.strokeColor = data.box_stroke_color;
	tempS.lineWidth = data.box_line_width;
	tempS.lineCap = data.box_line_cap;
	tempC.boxes[data.box_idx] = tempS;

	mainViewFrame.clear(tempC.ctx);
	tempC.draw();
	if(mainViewFrame.parentC==tempC){
		loadCanvas(data.canvas_idx);
	}
});

socket.on("delBox", function (data){
	tempC = canvases[data.canvas_idx];

	delete tempC.boxes[data.box_idx];

	mainViewFrame.clear(tempC.ctx);
	tempC.draw();
	if(mainViewFrame.parentC==tempC){
		loadCanvas(data.canvas_idx);
	}
});

socket.on("addCanvas",function (data){
	if($("#canvas"+data.canvas_idx).length || canvases.length!=data.canvas_idx){
		return;
	}

	var makeCanvas = $("<canvas id='canvas"+data.canvas_idx+"' canvasNum='"+data.canvas_idx+"' width='640' height='480' style='border:1px #ddd solid;width:280px;height:200px;' onclick='loadCanvas("+data.canvas_idx+");'>This text is displayed if your browser does not support HTML5 Canvas.</canvas>");
	makeCanvas.appendTo("#canvasView");

	var c = new canvas();
	c.background = data.canvas_bg;
	c.ctx = makeCanvas.get(0).getContext("2d");

	canvases[data.canvas_idx] = c;
});


function setRoom(){
	socket.emit("setRoom", project_idx);
}

function send() {
	socket.emit("sendMessage", {user_name : user_name, message : input.value});
	chatStory += "Me : " + input.value +"\n";
	//overal();
	input.value = "";
}

function overal()
{
	$("#message_list").scrollTop($("#message_list")[0].scrollHeight+20);
}

function layer_popup()
{
	if(pop == 0){
		var layer = document.getElementById("chat");
		colorChange();
		layer.style.visibility="visible";
		pop = 1;
		pushChat();
	}
	else{
		var layer = document.getElementById("chat");
		colorChange();
		layer.style.visibility="hidden";
		pop = 0;
		push = 0;
	}
}

function pushChat()
{
	
	if(pop == 0 && this.push == 0){
		var pushChange = document.getElementById("pushpush");
		pushChange.style.visibility="visible";
		this.push = 1;
	}
	else
	{
		var pushChange = document.getElementById("pushpush");
		pushChange.style.visibility="hidden";
	}
}

function colorChange()
{
	var color = document.getElementById("chat-button").style.background;
	if(color == "red")
		document.getElementById("chat-button").style.background="";
	else
		document.getElementById("chat-button").style.background="red";
}

</script>

</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="#">Project name</a>
          <div class="nav-collapse collapse">
            <p class="navbar-text pull-right">
              Logged in as <a href="#" class="navbar-link">Username</a>
            </p>
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#about">About</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	<div style="width:100%;height:100%;overflow:hidden;">
		<div id="leftTab" style="width:320px;height:100%;float:left; background-color:#B0E0E6;overflow-y:scroll;">
			<div class="well sidebar-nav" style="height:30px; background-color:#E0FFFF;">
				<ul class="nav nav-list">
				<li class="nav-header" style="font-weight:bold; color:#1E90FF;">프레젠테이션 리스트</li></ul></div>
			<div id="canvasView" style="text-align:center; padding:7px;"></div>
			<div style="text-align:center; padding:7px; border:1px #ddd solid; width:280px; height:200px;" onclick="addCanvas();">+</div>
		</div>
		<div id="rightTab" style="height:100%;float:left;">
			<iframe id="mainViewFrame" src="project/mainView" frameborder="0" scrolling="yes" allowTransparency="false" style="background-color:#eee;"></iframe>
		</div>
	</div>
	<!-- 푸쉬알림 -->
	<div class="bubble" id="pushpush" style="position:absolute; clear:left; float:right; right:18px; bottom:20px; visibility: hidden;">
		<p class="lab">
			메시지가 도착했습니다.
		</p>
	</div>
	<!-- 채팅창 -->
	<div style="position:absolute; clear:left; float:right; right:18px; bottom:0px;">
		<input id="chat-button" type="button" onclick="layer_popup()" style ="background:red; filiter:alpha(opacity=10); opacity:0.5; -moz-opacity:0.5;" value="채팅">
	</div>
	<div id="chat" style="position:absolute; clear:left; float:right; right:18px; bottom:20px; border:double; width:300px; height:250px; visibility: hidden; background-color:white;">
		<div id ="message_list" style="position:absolute; width:300px; height:200px; overFlow:auto;"></div>
		<div id="inputText" style="position:absolute; float:left; width:300px; height:50px; left:0; bottom:0px;">
			<input id = "input" onkeydown="if (event.keyCode == 13) send()" type="message">
			<button onclick="javascript:send();setRoom();" type="button">보내기</button>
		</div>
	</div>
</body>
</html>