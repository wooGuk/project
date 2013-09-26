<style>
	#projectListTable tr { color:blue; }
</style>

<link rel="stylesheet" href="application/js/common/jquery-ui.css" />
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
<script src="application/js/common/jquery-2.0.2.min.js"></script>
<script src="application/js/common/jquery-ui.min.js"></script>
<link href="application/js/bootstrap-2.3.2/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">

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
			$("#project_name").val($(this).attr("pjname"));
			$("#loadProject").submit();
		}
	});
});

function addList(project_idx, project_name){
	htmlStr = "<tr id='"+project_idx+"'><td>"+project_name+"</td></tr>";
	$("#showAddPJDiv").after(htmlStr);
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

	<table id="projectListTable" align="center">
		<col width=200>
		<tr id="showAddPJDiv"><td>+</td></tr>
<?php
	if(isset($list)&&sizeof($list)>0)
	{
		foreach($list as $row)
		{
?>
		<tr id="<?=$row->project_idx;?>" pjname="<?=$row->project_name;?>"><td><?=$row->project_name;?></td></tr>
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
		<input type="hidden" id="project_name" name="project_name" />
	</form>