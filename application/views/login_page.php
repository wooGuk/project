




<!DOCTYPE html>
<html lang="kor">
<head>
	<meta charset="utf-8">
	<title>Login Page</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
  <meta charset="utf-8">
  <title>Login Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
    <meta name="author" content="">

      <link href="./application/js/bootstrap-2.3.2/docs/assets/css/bootstrap.css" rel="stylesheet">
    <link href="application/js/bootstrap-2.3.2/docs/assets/css/bootstrap.css" rel="stylesheet">
    <link href="application/js/bootstrap-2.3.2/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-signin {
        max-width: 300px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="/application/js/bootstrap-2.3.2/docs/assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
      <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
                    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
                                   <link rel="shortcut icon" href="../assets/ico/favicon.png">













</head>
<body>

<div class="container">
	 <form class="form-signin">
	<h2 class="form-signin-heading">Login Page</h2>
	<input type="text" id="email" placeholder="이메일주소"/>
	<input type="text" id="passwd" placeholder="비밀번호"/>
	<button id="login" class="btn btn-large btn-primary">로그인</button>
</div>
<div class="container">
	 <h2 class="form-signin-heading">Login Page</h2>
	 <input type="text" id="email" placeholder="이메일주소"/>
	 <input type="text" id="passwd" placeholder="비밀번호"/>
	 <button id="login" class="btn btn-large btn-primary">로그인</button>
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