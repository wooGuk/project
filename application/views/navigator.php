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
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>