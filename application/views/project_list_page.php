<title>Project List</title>

<link href="application/js/bootstrap-2.3.2/docs/assets/css/bootstrap.css" type="text/css" rel="stylesheet"/>
<style type="text/css">
	#projectListTable { list-style-type: none; margin: 0; padding: 0; width:500px; }
	#projectListTable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; cursor:pointer;}
	#projectListTable li span { position: absolute; margin-left: -1.3em; }
</style>
<link href="application/js/bootstrap-2.3.2/docs/assets/css/bootstrap-responsive.css" type="text/css" rel="stylesheet"/>
<link rel="stylesheet" href="application/js/common/jquery-ui.css" />

<script src="application/js/common/jquery-2.0.2.min.js"></script>
<script src="application/js/common/jquery-ui.min.js"></script>

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

	$("#projectListTable li").click(function(){
		if($(this).attr("id")=="showAddPJDiv"){
			$("#addPJDiv").dialog("open");
		}else{
			loadProject($(this).attr("id"), $(this).attr("pjname"));
		}
	});

	$("#projectListTable").sortable({
		items: "li:not(.ui-state-disabled)",
		activate: function(event, ui){
			console.log(1);
		}
	});
	$( "#projectListTable" ).disableSelection();


});

function loadProject(id, name){
	$("#project_idx").val(id);
	$("#project_name").val(name);
	$("#loadProject").submit();
}

function addList(project_idx, project_name){
	htmlStr = "<li class='ui-state-default' id='"+project_idx+"' pjname='"+project_name+"' onclick='loadProject("+project_idx+", "+'"'+project_name+'"'+")'>"+project_name+"</li>";
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

<div style="width:500px; margin-left:auto;margin-right:auto;">
	<ul id="projectListTable" align="center">
		<li id="showAddPJDiv" class="ui-state-disabled" style="cursor:pointer !important;">+ 새로운 프로젝트를 생성합니다.</li>
<?php
	if(isset($list)&&sizeof($list)>0)
	{
		foreach($list as $row)
		{
?>
		<li id="<?=$row->project_idx;?>" pjname="<?=$row->project_name;?>" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span><?=$row->project_name;?></li>
<?php
		}
	}
?>
	</ul>
</div>

	<div id="addPJDiv" title="프로젝트 생성">
		프로젝트 이름 : <input type="text" id="addPJname" />
	</div>
	<form id="loadProject" action="project" method="post">
		<input type="hidden" id="project_idx" name="project_idx" />
		<input type="hidden" id="project_name" name="project_name" />
	</form>