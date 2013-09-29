
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>

				<?php if($name!="프로젝트"):?>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<?php endif;?>

				<span class="icon-bar"></span>
			</button>
			<p class="brand" style="height:0px;"><?=$name;?></p>
			<div class="nav-collapse collapse">
				<p class="navbar-text pull-right">
					<span style="margin-right:10px;"><?=$user_name;?>님</span><a href="/CI/logout" class="navbar-link">로그아웃</a>
				</p>
				<?php if($name!="프로젝트"):?>
					<ul class="nav">
						<li class="active"><a href="/CI/project_list">프로젝트 리스트 보기</a></li>
					</ul>
					<ul class="nav">
						<li class="active"><a href="/CI/slide_show">슬라이드쇼 보기</a></li>
					</ul>
				<?php endif;?>
				<ul class="nav">
					<li class="active"><a id="invite" style="cursor:pointer;">초대</a></li>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>

<div id="invitePop" title="프로젝트 초대" style="display:none;">
<?php 	if(!$this->session->userdata('project_idx'))
		{
?>
	<select id="inviteProjectIdx">
<?php
			if(isset($list)&&sizeof($list)>0)
			{
				foreach($list as $row)
				{
?>
				<option value="<?=$row->project_idx;?>"><?=$row->project_name;?></option>
<?php
				}
			}
?>
	</select><br>
<?php
		}else{
?>
	<input id="inviteProjectIdx" type="text" style="display:none;" value="<?=$this->session->userdata('project_idx')?>"/><br>
<?php
		}
?>
	초대할 아이디 : <input id="inviteUserId" type="text" style="display:inline; width:80px"/><br>
	수락 가능 기간 : <input id="limitDate" type="text" style="display:inline; width:60px"/>일
</div>

<link rel="stylesheet" href="application/js/common/jquery-ui.css" />
<script src="application/js/common/jquery-2.0.2.min.js"></script>
<script src="application/js/common/jquery-ui.min.js"></script>
<script>
$(function(){
	$("#invitePop").dialog({
		autoOpen : false,
		show: { effect: "blind", duration: 500 },
		hide: { effect: "blind", duration: 500 },
		resizable: false,
		height:200,
		modal: true,
		buttons: {
			"초대": function() {
				invitePJ();
			},
			"취소": function() {
				$(this).dialog("close");
			}
		},
		close : function(){
			$("#invite_user_id").val("");
		}
	});

	$("#invite").click(function(){
		$("#invitePop").dialog("open");
	});
});

function invitePJ(){
	$.post("project_list/invite_project/", {
		project_idx : $("#inviteProjectIdx").val()
		, user_id : $("#inviteUserId").val()
		, limit : $("#limitDate").val()
	}, function(redata){
		if($.isNumeric(redata)&&redata>0){
			alert("성공적으로 초대되었습니다.");
			$("#invitePop").dialog("close");
		}else if($("#"+redata).length==1){
			if(redata=="inviteProjectIdx"){
				alert("초대할 프로젝트를 생성해야합니다.");
				$("#invitePop").dialog("close");
				return;
			}else if(redata=="inviteUserId"){
				alert("초대할 사람의 ID를 입력해주세요.");
				$("#inviteUserId").focus();
				return;
			}else if(redata=="limitDate"){
				alert("수락 가능 기간을 입력해주세요.");
				$("#limitDate").focus();
				return;
			}
		}else if(redata=="error"){
			alert("존재하지 않는 아이디입니다.");
		}else if(redata=="be_found"){
			alert("이미 초대되었습니다.");
		}
	});
}
</script>