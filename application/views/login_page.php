<!DOCTYPE html>
<html lang="kor">
<head>
	<meta charset="utf-8">
	<title>Login Page</title>
</head>
<body>

<div>
	Login Page
	<input type="text" id="email" />
	<input type="text" id="passwd" />
	<input type="button" id="login" value="login" />
</div>

</body>


<script src="application/js/common/jquery-2.0.2.min.js"></script>
<script>
$(function(){
	$("#login").click(function(){
		$.post("login/loginCheck/", {email : $("#email").val(), passwd : $("#passwd").val()}, function(redata){
			if(redata=='success'){
				window.location = "project_list";
			}else if(redata=="error"){
				alert("이메일과 비밀번호가 일치하지 않습니다.");
			}else if($("#"+redata).length==1){
				alert("입력되지 않은 정보가 있습니다.");
				$("#"+redata).focus();
			}
		});
	});
});
</script>
</html>