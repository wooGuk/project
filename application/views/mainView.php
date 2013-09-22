<!DOCTYPE html>
<html lang="kor">
<head>
	<meta charset="utf-8">
	<title>Project List</title>

<style>
	html, body { padding:0; margin:0; }
</style>

<script src="../application/js/common/jquery-2.0.2.min.js"></script>
<script src="../application/js/shapeList.js"></script>
<script src="../application/js/shape.js"></script>
<script src="../application/js/canvas.js"></script>

<script src="http://203.253.20.234:8005/socket.io/socket.io.js"></script>
<link rel="Stylesheet" type="text/css" href="../application/js/figureButton.css" />
<!-- wColorPicker -->
<link rel="Stylesheet" type="text/css" href="../application/js/common/wColorPicker.css" />
<script type="text/javascript" src="../application/js/common/wColorPicker.js"></script>

<script>

$(window).resize(function(){
	pWidth = $(parent.mainViewFrame).width();
	pHeight = $(parent.mainViewFrame).height();

	$("body").width(pWidth);
	$("body").height(pHeight-30);

	r = pWidth/640;

	//$("#mainCanvas").css("zoom", r);
	//$("#gcanvas").css("zoom", r);
});

$(function(){
	parent.setRoom();
	parent.preLoad();

	pWidth = $(parent.mainViewFrame).width();
	pHeight = $(parent.mainViewFrame).height();

	$("body").width(pWidth);
	$("body").height(pHeight-30);

	var sx, sy, ex, ey;
	var down_flag = 0;
	var resize_flag = 0;
	var resize_box = -1;
	var resize_point = -1;

	/*gs없애고 selList가 1일 때 gs로 draw*/
	$(document).mousemove(function(e){
		if(down_flag==1){

		}
	});

	$("#mainCanvas").mousedown(function(e){
		timer = setInterval(function(){intervalDraw();}, 20);

		sx = e.offsetX;
		sy = e.offsetY;
		down_flag = 1;

		if(shapeName=="select"){

			// 선택된 도형 중에 resize하려고 할 때
			for(var i in mainC.selList){
				if(resize_box==i){
					resize_flag = 1;
					return;
				}
			}

			// move
			var pixel = mainC.ctx.getImageData(e.offsetX, e.offsetY, 1, 1);
			if(pixel.data[3]>0){
				for(i=mainC.boxes.length-1; i>=0; i--){
					clear(mainC.gctx);
					mainC.boxes[i].draw(mainC.gctx);
					var gpixel = mainC.gctx.getImageData(sx, sy, 1, 1);
					if(gpixel.data[3] > 0){
						if(mainC.selList[i]==1){
							// 이미 선택한 도형 위에 클릭했을 때
							if(control_flag == 1){
								mainC.unSelect(i);
							}
							break;
						}else{
							if(control_flag == 1){
								mainC.select(i);
							}else{
								mainC.selectArea(sx,sy,sx,sy);
							}
						}
					}
				}
			}else{
				// move가 아닐 때는 selList비워줌.	
				clearSelList();
			}

		}else{
			clearSelList();

			newShape = new shape();
			newShape.name = shapeName;
			newShape.sx = sx;
			newShape.sy = sy;
			newShape.ex = sx;
			newShape.ey = sy;
			tempS = mainC.addShape(newShape, 1);
			tempS = mainC.addShape(newShape, 0); // 사라질 임시 도형
			mainC.gs[mainC.boxes.length-1] = tempS;
		}
	});

	// 0  1  2
    // 3     4
    // 5  6  7

	function setPoint(c, i, point){
		box = mainC.gs[i];
		rBox = mainC.boxes[i];

		switch(c){
			case "x1" : 
				if(rBox.sx==rBox.x){
					box.sx = point;
				}else{
					box.ex = point;
				}
				break;
			case "y1" : 
				if(rBox.sy==rBox.y){
					box.sy = point;
				}else{
					box.ey = point;
				}
				break;
			case "x2" : 
				if(rBox.sx==rBox.x){
					box.ex = point;
				}else{
					box.sx = point;
				}
				break;
			case "y2" : 
				if(rBox.sy==rBox.y){
					box.ey = point;
				}else{
					box.sy = point;
				}
				break;
		}
    }
	$("#mainCanvas").mousemove(function(e){
		if(shapeName=="select"){
			if(resize_flag==1){
				for(i in mainC.selList){
					var t_box = mainC.gs[i];
					switch(resize_point){
						case 0 :
							setPoint("x1", i, e.offsetX);
							setPoint("y1", i, e.offsetY);
							mainC.gs[i] = mainC.addShape(mainC.gs[i], 1);
							copyStyle(mainC.gs[i], mainC.boxes[i]);
							break;
						case 1 :
							setPoint("y1", i, e.offsetY);
							mainC.gs[i] = mainC.addShape(mainC.gs[i], 1);
							copyStyle(mainC.gs[i], mainC.boxes[i]);
							break;
						case 2 :
							setPoint("x2", i, e.offsetX);
							setPoint("y1", i, e.offsetY);
							mainC.gs[i] = mainC.addShape(mainC.gs[i], 1);
							copyStyle(mainC.gs[i], mainC.boxes[i]);
							break;
						case 3 :
							setPoint("x1", i, e.offsetX);
							mainC.gs[i] = mainC.addShape(mainC.gs[i], 1);
							copyStyle(mainC.gs[i], mainC.boxes[i]);
							break;
						case 4 :
							setPoint("x2", i, e.offsetX);
							mainC.gs[i] = mainC.addShape(mainC.gs[i], 1);
							copyStyle(mainC.gs[i], mainC.boxes[i]);
							break;
						case 5 :
							setPoint("x1", i, e.offsetX);
							setPoint("y2", i, e.offsetY);
							mainC.gs[i] = mainC.addShape(mainC.gs[i], 1);
							copyStyle(mainC.gs[i], mainC.boxes[i]);
							break;
						case 6 :
							setPoint("y2", i, e.offsetY);
							mainC.gs[i] = mainC.addShape(mainC.gs[i], 1);
							copyStyle(mainC.gs[i], mainC.boxes[i]);
							break;
						case 7 :
							setPoint("x2", i, e.offsetX);
							setPoint("y2", i, e.offsetY);
							mainC.gs[i] = mainC.addShape(mainC.gs[i], 1);
							copyStyle(mainC.gs[i], mainC.boxes[i]);
							break;
					}
				}
			}

			var pixel = mainC.ctx.getImageData(e.offsetX, e.offsetY, 1, 1);
			if(pixel.data[3]>0){
				if(resize_flag==0){
					$(this).css("cursor", "move");
					resize_box = -1;
				}
				if(mainC.selList.length>0){
					for(i in mainC.selList){

						// 마우스 커서 변경
						if(mainC.selList[i]==1 && resize_flag==0){
							for (var j=0; j<8; j++) {
								var cur = mainC.gs[i].selectionHandles[j];
								if(e.offsetX >= cur.x && e.offsetX <= cur.x+cur.selBoxSize && e.offsetY >= cur.y && e.offsetY <= cur.y+cur.selBoxSize ){
									switch(j){
										case 0 :
											$(this).css("cursor", "nw-resize");
											break;
										case 1 :
											$(this).css("cursor", "n-resize");
											break;
										case 2 :
											$(this).css("cursor", "ne-resize");
											break;
										case 3 :
											$(this).css("cursor", "w-resize");
											break;
										case 4 :
											$(this).css("cursor", "e-resize");
											break;
										case 5 :
											$(this).css("cursor", "sw-resize");
											break;
										case 6 :
											$(this).css("cursor", "s-resize");
											break;
										case 7 :
											$(this).css("cursor", "se-resize");
											break;
									}
									
									resize_point = j;
									resize_box = i;
								}
							}
						}

					}
				}
				
			}else{
				$(this).css("cursor", "Auto");
			}

			if(down_flag==1&&resize_flag==0){
				if(mainC.selList.length>0){
					diffX = sx-e.offsetX;
					diffY = sy-e.offsetY;
					sx = e.offsetX;
					sy = e.offsetY;
					for(i in mainC.selList){
						mainC.gs[i].sx -= diffX;
						mainC.gs[i].ex -= diffX;
						mainC.gs[i].sy -= diffY;
						mainC.gs[i].ey -= diffY;
						mainC.gs[i].x -= diffX;
						mainC.gs[i].y -= diffY;
					}
				}
			}
			
		}else{
			$(this).css("cursor", "crosshair");
			if(down_flag==1){
				newShape = new shape();
				newShape.name = shapeName;
				newShape.sx = sx;
				newShape.sy = sy;
				newShape.ex = e.offsetX;
				newShape.ey = e.offsetY;
				tempS = mainC.addShape(newShape, 1);
				mainC.gs[mainC.boxes.length-1] = tempS;
			}
		}
	});

	$("#mainCanvas").mouseup(function(e){
		ex = e.offsetX;
		ey = e.offsetY;
		down_flag = 0;
		resize_flag = 0;
		resize_box = -1;
		resize_point = -1;

		if(shapeName=="select"){
			// 여러개 선택할 경우
			if(mainC.selList.length==0){
				mainC.selectArea(sx, sy, ex, ey);
			}

			// move
			for(i in mainC.selList){
				copyPosition(parentC.boxes[i], mainC.gs[i]);
				getBoxFromparent(i);

				updateBox(i);
			}
		
		}else{
			parentC.sColor = mainC.sColor;
			parentC.fColor = mainC.fColor;

			newShape = new shape();
			newShape.name = shapeName;
			newShape.sx = sx;
			newShape.sy = sy;
			newShape.ex = ex;
			newShape.ey = ey;
			box = parentC.addShape(newShape, 0);

			parent.socket.emit("addBox", {
				box_idx : mainC.boxes.length-1
				, canvas_idx : parent.socket.parentNum
				, project_idx : parent.project_idx
				, box_name : box.name
				, box_x : box.x
				, box_y : box.y
				, box_w : box.w
				, box_h : box.h
				, box_sx : box.sx
				, box_sy : box.sy
				, box_ex : box.ex
				, box_ey : box.ey
				, box_alpha : box.alpha
				, box_fill_color : box.fillColor
				, box_stroke_color : box.strokeColor
				, box_line_width : box.lineWidth
				, box_line_cap : box.lineCap
				, box_text : box.text
			});

			mainC.select($(mainC.boxes).length-1);
			getBoxFromparent(-1);
			shapeName = "select";
		}

		clearInterval(timer);
		intervalDraw();
		console.log(2);
	});

	function updateBox(i){
		var box = mainC.boxes[i];
		parent.socket.emit("modifyBox", {
			box_idx : i
			, canvas_idx : parent.socket.parentNum
			, project_idx : parent.project_idx
			, box_name : box.name
			, box_x : box.x
			, box_y : box.y
			, box_w : box.w
			, box_h : box.h
			, box_sx : box.sx
			, box_sy : box.sy
			, box_ex : box.ex
			, box_ey : box.ey
			, box_alpha : box.alpha
			, box_fill_color : box.fillColor
			, box_stroke_color : box.strokeColor
			, box_line_width : box.lineWidth
			, box_line_cap : box.lineCap
			, box_text : box.text
		});
	}
	
	$("#fillColorPicker").wColorPicker({
		mode: "click",
		initColor: "#CCFF00",
		buttonSize: 10,
		showSpeed: 300,
		hideSpeed: 300,
		onSelect: function(selectColor){
			mainC.fColor = selectColor;
			if($(mainC.selList).length > 0){
				for(var i in mainC.selList){
					parentC.setFillColor(i, selectColor);
					mainC.gs[i].fillColor = selectColor;
					getBoxFromparent(i);
					updateBox(i);
					intervalDraw();
				}
			}
		}
	});

	$("#strokeColorPicker").wColorPicker({
		mode: "click",
		initColor: "#660066",
		buttonSize: 10,
		showSpeed: 300,
		hideSpeed: 300,
		onSelect: function(selectColor){
			mainC.sColor = selectColor;
			if($(mainC.selList).length > 0){
				for(var i in mainC.selList){
					parentC.setStrokeColor(i, selectColor);
					mainC.gs[i].strokeColor = selectColor;
					getBoxFromparent(i);
					updateBox(i);
					intervalDraw();
				}
			}
		}
	});
});

var shapeName = "select";
var newShape;
var mainC =  new canvas();
var parentC = null;
var control_flag = 0;
var tempShape=[];
var timer;


function setShape(name){
	shapeName = name;
}

function copyPosition(a, b){
	a.x = b.x;
	a.y = b.y;
	a.w = b.w;
	a.h = b.h;
	a.sx = b.sx;
	a.sy = b.sy;
	a.ex = b.ex;
	a.ey = b.ey;
}

function copyStyle(a, b){
	a.fillColor = b.fillColor;
	a.strokeColor = b.strokeColor;
	a.lineWidth = b.lineWidth;
	a.lineCap = b.lineCap;
	a.text = b.text;
}

function getBoxFromparent(i){
	if(i > -1){
		mainC.boxes[i] = parentC.boxes[i];
		return;
	}

	clearBox(mainC);
	mainC.gs = [];
	for(i=0; i<parentC.boxes.length; i++){
		mainC.boxes[i] = parentC.boxes[i];

		newShape = new shape();
		newShape.name = parentC.boxes[i].name;
		copyPosition(newShape, parentC.boxes[i]);
		tempS = mainC.addShape(newShape, 1);
		copyStyle(tempS, parentC.boxes[i]);
		
		mainC.gs[i] = tempS;
	}
}

function loadCanvas(canvasNum){
	// 부모에서 도형리스트 복사
	parent.socket.parentNum = canvasNum;
	parentC = parent.canvases[canvasNum];
	getBoxFromparent(-1);

	// 고스트 캔버스 선택
	var position = $("#mainCanvas").position();
	var gCanvas = $("<canvas id='gcanvas' width='640' height='480' style='position:absolute; z-index:-1;');'>This text is displayed if your browser does not support HTML5 Canvas.</canvas>");
	gCanvas.css("top", position.top);
	gCanvas.css("left", position.left);
	gCanvas.insertAfter("#mainCanvas");
	mainC.gctx = gCanvas.get(0).getContext("2d");

	// 캔버스 선택
	mainC.ctx = $("#mainCanvas").get(0).getContext("2d");
	
	// 선택 초기화
	clearSelList();

	//timer = setInterval(function(){intervalDraw();}, 20);
	intervalDraw();
}

function clear(ctx){
	ctx.clearRect(0,0,640,480);
}

function clearBox(canvas){
	canvas.boxes = [];
}

function clearSelList(){
	mainC.selList = [];
}

/* 후에 validation을 줄것인지? 의논해봅시다. 아직은 버벅거리지 않음. */
function intervalDraw(){
	console.log("draw");
	$("#textDiv").html("");
	clear(mainC.ctx);
	mainC.draw();
	clear(parentC.ctx);
	parentC.draw();
}


function submitBox(s){
	var box = {
		name : s.name
		,x : s.x
		,y : s.y
		,sx : s.sx
		,sy : s.sy
		,ex : s.ex
		,ey : s.ey
		,alpha : s.alpha
		,fillColor : s.fillColor
		,strokeColor : s.strokeColor
		,lineWidth : s.lineWidth
		,lineCap : s.lineCap
		,text : s.text
	}
	return box;
}
</script>



<script>
$(document).keyup(function(e){
	if($("textarea").is(":focus")){
		return;
	}

	switch(e.keyCode){
		case 17 : //ctrl
			control_flag = 0;
			break;
		case 65 : //a
			if(control_flag==1){
				for(i=0; i<mainC.boxes.length; i++)
				{
					mainC.select(i);
				}
			}
			break;
		case 67 : //c
			if(control_flag==1){
				tempShape = [];
				for(i=mainC.selList.length-1; i>=0; i--)
				{
					if(mainC.selList[i] == 1)
					{
						newShape = new shape();
						newShape.name = mainC.gs[i].name;
						newShape.sx = mainC.gs[i].sx+5;
						newShape.sy = mainC.gs[i].sy+5;
						newShape.ex = mainC.gs[i].ex+5;
						newShape.ey = mainC.gs[i].ey+5;
						newShape.fillColor = mainC.boxes[i].fillColor;
						newShape.strokeColor = mainC.boxes[i].strokeColor;
						tempShape.push(newShape);
					}	
				}
			}
			break;
		case 86 : //v
			if(control_flag==1){
				clearSelList();
				for(i = 0 ; i< tempShape.length ; i++)
				{
					tempSc = tempShape[i].strokeColor;
					tempFc = tempShape[i].fillColor;
					tempS = mainC.addShape(tempShape[i], 0);
					tempS = mainC.addShape(tempShape[i], 1);
					tempS.fillColor = tempFc;
					tempS.strokeColor = tempSc;
					mainC.gs[mainC.boxes.length-1] = tempS;
					
					newShape = new shape();
					newShape.name = tempShape[i].name;
					newShape.sx = tempShape[i].sx;
					newShape.sy = tempShape[i].sy;
					newShape.ex = tempShape[i].ex;
					newShape.ey = tempShape[i].ey;
					newShape.strokeColor = tempSc;
					newShape.fillColor = tempFc;

					parentC.sColor = newShape.strokeColor;
					parentC.fColor = newShape.fillColor;

					parentC.addShape(newShape, 0);
					mainC.select(parentC.boxes.length-1);
					getBoxFromparent(-1);
				}
			}
			shapeName = "select";
			break;
	}
	
	intervalDraw();
});

$(document).keydown(function(e){
	if($("textarea").is(":focus")){
		return;
	}

	// ctrl 이용
	switch(e.keyCode){
		case 17 : //ctrl
			control_flag = 1;
			break;
	}
 	
 	switch(e.keyCode){
 		case 37 : // ←
 			for(i=mainC.selList.length-1; i>=0;i--){
				if(mainC.selList[i] == 1)
				{	
					if(mainC.gs[i].sx != 0)
					{
						mainC.gs[i].sx -= 1;
						mainC.gs[i].ex -= 1;
						mainC.gs[i].x -= 1;
					}

					copyPosition(parentC.boxes[i], mainC.gs[i]);
					getBoxFromparent(i);
				}
			}
			break;
		case 38 : // ↑
			for(i=mainC.selList.length-1; i>=0;i--){
				if(mainC.selList[i] == 1)
				{	
					if(mainC.gs[i].sy != 0)
					{
						mainC.gs[i].sy -= 1;
						mainC.gs[i].ey -= 1;
						mainC.gs[i].y -= 1;
					}
					copyPosition(parentC.boxes[i], mainC.gs[i]);
					getBoxFromparent(i);
				}
			}
			break;
		case 39 : // →
			for(i=mainC.selList.length-1; i>=0;i--){
				if(mainC.selList[i] == 1)
				{
					if(mainC.gs[i].ex != 640)
					{
						mainC.gs[i].sx += 1;
						mainC.gs[i].ex += 1;
						mainC.gs[i].x += 1;
					}
					copyPosition(parentC.boxes[i], mainC.gs[i]);
					getBoxFromparent(i);
				}
			}
			break;
		case 40 : // ↓
			for(i=mainC.selList.length-1; i>=0;i--){
				if(mainC.selList[i] == 1)
				{	
					if(mainC.gs[i].ey != 400)
					{
						mainC.gs[i].sy += 1;
						mainC.gs[i].ey += 1;
						mainC.gs[i].y += 1;
					}
					copyPosition(parentC.boxes[i], mainC.gs[i]);
					getBoxFromparent(i);
				}
			}
			break;
		case 46 : // del
			for(i=mainC.selList.length-1; i>=0;i--){
				if(mainC.selList[i] == 1)
				{
					parentC.boxes.splice(i,1);
					mainC.unSelect(i);
					mainC.gs.splice(i,1);
					getBoxFromparent();
					
				}
			}
			break;
	}

	intervalDraw();
	return false;
});

</script>
<script>
	$(function(){
		$(".btndraw").click(function(){
			$("#figureList").show();
			}).mouseenter(function(){
			$("#figureList").hide();
		});

		$(".shapeGroup").mouseover(function(){
			$(this).children().show();
		}).mouseleave(function(){
			$(this).children().hide();
			});
		$(".figure").click(function(){
			$("#figureList").hide();
			});
		$(".arrowlist").click(function(){
			$("#figureList").hide();
			});
		$(".flowlist").click(function(){
			$("#figureList").hide();
			});
		$(".extralist").click(function(){
			$("#figureList").hide();
			});
		$(".callist").click(function(){
			$("#figureList").hide();
			});
	});
		
</script>
</head>
<body>
	<div id="btnGroup" style="min-width:700px; height:30px; background-color:#87CEEB;">
		<span><a type="button" class="btn" onclick="setShape('select');">asdasdasd</a></span>
		<span><a type="button" class="btndraw">asdasd</a></span>
		<table id="figureList" style="backgrond:url(../application/img/menu.png) no-repeat center; position:absolute; top:30px; left:70px; height:35px; display:none;">
			<tr>
				<td class="shapeGroup" style="background: url(../application/img/basicRect.png) no-repeat center; background-size:100%; background-color:#a9a9a9; width:30px; height:30px; ">
					<table class="figure" style="border:10px solid #ddd;position:absolute; top:30px; left:0px; display:none; width:400px; background-color:#fff;">
						<tr>
							<td style="border-bottom:2px solid #ddd;" colspan="5">사각형</td>
						</tr>
						<tr style="height:40px">
							<td  type="button" class="figureRect" onclick="setShape('rect');" width="50"></td>
							<td  type="button" class="roundRect" onclick="setShape('roundRect');" width="50"></td>
							<td   type="button" class="oneCutRect" onclick="setShape('oneCuttedRect');"  width="50"></td>
							<td  type="button" class="twoCutRect" onclick="setShape('bonthCuttedRect');" width="50"></td>
							<td  type="button" class="counterCutRect" onclick="setShape('counterCuttedRect');" width="50"></td>
						</tr>
						<tr style="height:40px">
							<td  type="button" class="oneRoundCutRect" onclick="setShape('cuttedRoundRect');" width="50"></td>
							<td  type="button" class="oneRoundRect" onclick="setShape('oneRoundRect');" width="50"></td>
							<td  type="button" class="bothRoundRect" onclick="setShape('twoRoundRect');" width="50"></td>
							<td  type="button" class="counterRoundRect" onclick="setShape('counterRoundRect');" width="50"></td>
							<td></td>
						</tr>
						<tr>
							<td style="border-bottom:2px solid #ddd;" colspan="5">기본도형</td>
						</tr>
						<tr style="height:40px">
							<td  type="button" class="triangle" onclick="setShape('triangle');" width="50"></td>
							<td  type="button" class="reversetriangle" onclick="setShape('reverseTriangle');" width="50"></td>
							<td  type="button" class="righttriangle" onclick="setShape('rightAngelTriangle');" width="50"> </td>
							<td  type="button" class="trapezoid" onclick="setShape('trapezoid');" width="50"></td>
							<td  type="button" class="diamond" onclick="setShape('diamond');" width="50"></td>
						</tr>
						<tr style="height:40px">
							<td  type="button" class="five" onclick="setShape('five');" width="50"></td>
							<td  type="button" class="six" onclick="setShape('six');" width="50"></td>
							<td  type="button" class="seven" onclick="setShape('seven');" width="50"></td>
							<td  type="button" class="eight" onclick="setShape('eight');" width="50"> </td>
							<td  type="button" class="ten" onclick="setShape('ten');" width="50"></td>
						</tr>
						<tr style="height:40px">
							<td  type="button" class="twelve" onclick="setShape('tewelve');" width="50"></td>
							<td  type="button" class="circle" onclick="setShape('circle');" width="50"></td>
						</tr>
					</table>
				</td>
				<td class="shapeGroup" style="background: url(../application/img/arrow1.png) no-repeat center; background-size:100%; width:30px; height:30px;">
					<table class="arrowlist" style="border:10px solid #ddd;position:absolute; top:30px; left:0px; display:none; width:400px; background-color:#fff;">
								<tr style="height:40px">
									<td  type="button" class="leftarrow" onclick="setShape('rightArrorw');" width="50"> </td>
									<td  type="button" class="rightarrow" onclick="setShape('leftArrorw');"  width="50"></td>
									<td	 type="button" class="uparrow" onclick="setShape('upArrorw');"  width="50"></td>
									<td  type="button" class="downarrow" onclick="setShape('downArrorw'); "  width="50"></td>
									<td  type="button" class="botharrow" onclick="setShape('bothSideArrorw');"  width="50"> </td>
								</tr>
								<tr style="height:48px">
									<td  type="button" class="updownarrow" onclick="setShape('upDownArrorw');" width="50"></td>
									<td  type="button" class="crossarrow" onclick="setShape('fourArrorw');" width="50"> </td>
									<td  type="button" class="threearrow" onclick="setShape('threewayArrorw');" width="50"></td>
									<td  type="button" class="rightturnarrow" onclick="setShape('rightCurveArrorw');" width="50"></td>
									<td  type="button" class="uarrow" onclick="setShape('uturnCurveArrorw');" width="50"></td>
								</tr>
								<tr style="height:48px">
									<td  type="button" class="leftuparrow" onclick="setShape('leftUpArrorw');" width="50"></td>
									<td  type="button" class="upfarrow" onclick="setShape('UpforwordArrorw');" width="50"></td>
									<td  type="button" class="rightcurvearrow" onclick="setShape('rightBendArrorw');" width="50"></td>
									<td  type="button" class="leftcurvearrow" onclick="setShape('leftBendArrorw');" width="50"></td>
									<td  type="button" class="rightexarrow" onclick="setShape('explainRightArrorw');" width="50"></td>
								</tr>
								<tr style="height:48px">
									<td  type="button" class="leftrightexarrow" onclick="setShape('explainRightLeftArrorw');" width="50"></td>
									<td  type="button" class-"fourexarrow"onclick="setShape('explainRightLeftUpDownArrorw');" width="50"></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</table>
				</td>
				<td class="shapeGroup" style="background: url(../application/img/flow.png) no-repeat center; background-size:100%; width:30px; height:30px;">
					<table class="flowlist"style="border:10px solid #ddd;position:absolute; top:30px; left:0px; display:none; width:400px; background-color:#fff;">
								<tr style="height:48px">
									<td  type="button" class="flowbasicarrow" onclick="setShape('flowChartBasic');" width="50"></td>
									<td  type="button" class="bothflowarrow" onclick="setShape('bothFlowChart');"  width="50"></td>
									<td	 type="button" class="saveflow" onclick="setShape('flowChartInnerSavaSpace');"  width="50"></td>
									<td  type="button" class="finalflow" onclick="setShape('flowChartFinal');" width="50"></td>
									<td  type="button" class="six" onclick="setShape('flowChartReady');" width="50"></td>
								</tr>
								<tr style="height:48px">
									<td  type="button" class="handflow1" onclick="setShape('flowChartHandWork');" width="50"></td>
									<td  type="button" class="handflow2" onclick="setShape('flowChartHandWork2');" width="50"></td>
									<td  type="button" class="chartcard" onclick="setShape('flowChartCard');" width="50"></td>
									<td  type="button" class="charttotal" onclick="setShape('flowChartTotal');" width="50"></td>
									<td  type="button" class="chartor" onclick="setShape('flowChartOr');" width="50"></td>
								</tr>
								<tr style="height:48px">
									<td  type="button" class="dataarray" onclick="setShape('flowChartDataArray');" width="50"></td>
									<td  type="button" class="chartarray" onclick="setShape('flowChartArray');" width="50"></td>
									<td  type="button" class="saveddata" onclick="setShape('flowChartStoredData');" width="50"></td>
									<td  type="button" class="disk" onclick="setShape('flowChartDisk');" width="50"></td>
									<td  type="button" class="delay" onclick="setShape('flowChartDelay');" width="50"></td>
								</tr>
								<tr style="height:48px">
									<td  type="button" class="directacces" onclick="setShape('flowChartDirectAccessDisk');" width="50"></td>
									<td  type="check" class="check" onclick="setShape('flowChartCheck');" width="50"></td>
									<td type="button" class="figureRect" onclick="setShape('rect');" width="50"></td>
									<td type="button" class="roundRect" onclick="setShape('roundRect');" width="50"></td>
									<td></td>
								</tr>
							</table>
				</td>
				<td class="shapeGroup" style="background: url(../application/img/basicheart.png) no-repeat center; background-size:100%; width:30px; height:30px;">
					<table class="extralist" style="border:10px solid #ddd;position:absolute; top:30px; left:0px; display:none; width:400px; background-color:#fff;">
								<tr style="height:48px">
									<td  type="button" class="heart" onclick="setShape('heart');" width="50"></td>
									<td  type="button" class="lightening" onclick="setShape('lightening');" width="50"></td>
									<td  type="button" class="cloud" onclick="setShape('cloud');" width="50"></td>
									<td  type="button" class="yes" onclick="setShape('yes');" width="50"></td>
									<td  type="button" class="no" onclick="setShape('no');" width="50"></td>
								</tr>
							</table>
						</td>

				<td class="shapeGroup" style="background: url(../application/img/basiccal.png) no-repeat center; background-size:100%; width:30px; height:30px;">
					<table class="callist" style="border:10px solid #ddd;position:absolute; top:30px; left:0px; display:none; width:400px; background-color:#fff;">
								<tr style="height:48px">
									<td  type="button" class="plus" onclick="setShape('plus');" width="50"></td>
									<td  type="button" class="minus" onclick="setShape('minus');"  width="50"></td>
									<td type="button" class="multiple" onclick="setShape('multiple');" width="50"></td>
									<td type="button" class="division" onclick="setShape('division');" width="50"></td>
									<td type="button" class="equal" onclick="setShape('equal');" width="50"></td>
								</tr>
								<tr style="height:48px">
									<td type="button" class="notequal" onclick="setShape('notEqual');" width="50"></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</table>
						</td>
			</tr>
		</table>
		<span><a type="button" class="btnLine" onclick="setShape('line');">asdasd</a></span>
		<span><img src="../application/img/brushtext.png" style="height:30px"/></span>
		<input type="text" id="brush_size" value="1" style="position:absolute; top:5px; width:50px; background-color:transparent;border:0.5 solid black;text-align:center;">
		<span><img src="../application/img/alpha.png" style="position:absolute; top:0px; left:320px; height:30px"/></span>
		<input type="text" id="opacity" value="1" style="position:absolute; top:5px; left:420px;width:50px; background-color:transparent;border:0.5 solid black;text-align:center;">
		<div style="display:inline;">
			<div><img src="../application/img/linecolor.png" style="position:absolute; top:1px; left:465px; height:30px"> </div>
			<div id="strokeColorPicker" style="position:absolute; top:5px; left:520px; display:inline;"></div>
			<div><img src="../application/img/figurecolor.png" style="position:absolute; top:1px; left:535px; height:30px"> </div>
			<div id="fillColorPicker" style="position:absolute; top:5px; left:580px;display:inline;"></div>
		</div>
	</div>

	<div id="textDiv" style="position:absolute; top:30px; left:0px; z-index:3;"></div>
	<canvas id="mainCanvas" width="640" height="480" style="background-color:#fff; zoom:1;"></canvas>
</body>
</html>