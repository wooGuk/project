<!DOCTYPE html>
<html lang="kor">
<head>
	<meta charset="utf-8">
	<title>Project List</title>
<style>
	#projectListTable tr { color:blue; }
</style>
<link rel="stylesheet" href="application/js/common/jquery-ui.css" />

<script src="application/js/common/jquery-2.0.2.min.js"></script>
<script src="application/js/common/jquery-ui.min.js"></script>
<script>

$(function(){
	$("#addPJDiv").dialog({
		autoOpen : false,
		show: { effect: "blind", duration: 500 },
		hide: { effect: "blind", duration: 500 },
		resizable: false,
		height:200,
		modal: true,
		buttons: {
			"생성": function() {
				addPJ();
			},
			"취소": function() {
				$(this).dialog("close");
			}
		},
		close : function(){
			$("#addPJname").val("");
		}
	});

	$("#projectListTable tr").click(function(){
		if($(this).attr("id")=="showAddPJDiv"){
			$("#addPJDiv").dialog("open");
		}else{
			$("#project_idx").val($(this).attr("id"));
			$("#loadProject").submit();
		}
	});
});

function addList(project_idx, project_name){
	htmlStr = "<tr id='"+project_idx+"'><td>"+project_name+"</td></tr>";
	$("#projectListTable").append(htmlStr);
}

function addPJ(){
	$.post("project_list/add_project/", {project_name : $("#addPJname").val()}, function(redata){
		if($("#"+redata).length==1){
			alert("생성할 프로젝트 이름을 입력해주세요.");
			$("#"+redata).focus();
		}else if(redata=="error"){
			alert("에러가 발생하였습니다.");
		}else{
			addList(redata, $("#addPJname").val());
			$("#addPJDiv").dialog("close");
		}
	});
}
</script>

</head>
<body>
	<table id="projectListTable">
		<col width=200>
		<tr id="showAddPJDiv"><td>+</td></tr>
<?php
	if(isset($list)&&sizeof($list)>0)
	{
		foreach($list as $row)
		{
?>
		<tr id="<?=$row->project_idx;?>"><td><?=$row->project_name;?></td></tr>
<?php
		}
	}
?>
	</table>

	<div id="addPJDiv" title="프로젝트 생성">
		프로젝트 이름 : <input type="text" id="addPJname" />
	</div>
	<form id="loadProject" action="project" method="post">
		<input type="hidden" id="project_idx" name="project_idx" />
	</form>
</body>
</html>