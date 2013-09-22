<!DOCTYPE html>
<html lang="kor">
<head>
	<meta charset="utf-8">
	<title>Logout Page</title>
</head>
<body>

<div>
	Logout Page
	<input type="button" id="logout" value="logout" />
</div>
<div id="logoutCheck" title="logout? :-)">
	<p>로그아웃 하시겠습니까?</p>
	<input type="button" id="logoutOK" value="YES" />
	<input type="button" id="logoutNO" value="NO" />
</div>
</body>

<script src="application/js/common/jquery-2.0.2.min.js"></script>
<script src="application/js/common/jquery-ui.min.js"></script>
<script>

$(function(){
	$("#logoutCheck").dialog ({
		autoOpen : false
	});
	$("#logout").click(function(){
		console.log("hi");
		$("#logoutCheck").dialog("open")
		{
			$("#logoutNO").click(function(){
				$("#logoutCheck").dialog("close");
			});
			$("#logoutYES").click(function(){
				$this->logoutLogic();
				$("#logoutCheck").dialog("close");
			});
		};
});
});
</script>
</html>