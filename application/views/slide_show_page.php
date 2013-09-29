<!DOCTYPE html>
<html lang="kor">
<head>
	<meta charset="utf-8">
	<title>SlideShow Page</title>
<meta name="viewport" content="width=device-width">
<style>
    body {
      -webkit-font-smoothing: antialiased;
      font: normal 15px/1.5 "Helvetica Neue", Helvetica, Arial, sans-serif;
      background-color: #000000;
      padding-top:10px;
    }

    #slides {
      display: none
    }

    #slides .slidesjs-navigation {
      margin-top:3px;
    }

    a.slidesjs-next,
    a.slidesjs-previous,
    a.slidesjs-play,
    a.slidesjs-stop {
      background-image: url(application/js/common/images/btns-next-prev.png);
      background-repeat: no-repeat;
      display:block;
      width:12px;
      height:18px;
      overflow: hidden;
      text-indent: -9999px;
      float: left;
      margin-right:5px;
    }

    a.slidesjs-next {
      margin-right:10px;
      background-position: -12px 0;
    }

    a:hover.slidesjs-next {
      background-position: -12px -18px;
    }

    a.slidesjs-previous {
      background-position: 0 0;
    }

    a:hover.slidesjs-previous {
      background-position: 0 -18px;
    }

    a.slidesjs-play {
      width:15px;
      background-position: -25px 0;
    }

    a:hover.slidesjs-play {
      background-position: -25px -18px;
    }

    a.slidesjs-stop {
      width:18px;
      background-position: -41px 0;
    }

    a:hover.slidesjs-stop {
      background-position: -41px -18px;
    }

    .slidesjs-pagination {
      margin: 7px 0 0;
      float: right;
      list-style: none;
    }

    .slidesjs-pagination li {
      float: left;
      margin: 0 1px;
    }

    .slidesjs-pagination li a {
      display: block;
      width: 13px;
      height: 0;
      padding-top: 13px;
      background-image: url(application/js/common/images/pagination.png);
      background-position: 0 0;
      float: left;
      overflow: hidden;
    }

    .slidesjs-pagination li a.active,
    .slidesjs-pagination li a:hover.active {
      background-position: 0 -13px
    }

    .slidesjs-pagination li a:hover {
      background-position: 0 -26px
    }

    #slides a:link,
    #slides a:visited {
      color: #333
    }

    #slides a:hover,
    #slides a:active {
      color: #9e2020
    }

    .navbar {
      overflow: hidden
    }
  </style>
  <!-- End SlidesJS Optional-->

  <!-- SlidesJS Required: These styles are required if you'd like a responsive slideshow -->
  <style>
    #slides {
      display: none;
      background-color: #FFFFFF;
    }

    .container {
      margin: 0 auto;
    }

    /* For tablets & smart phones */
    @media (max-width: 767px) {
      body {
        padding-left: 20px;
        padding-right: 20px;
      }
      .container {
        width: auto
      }
    }

    /* For smartphones */
    @media (max-width: 480px) {
      .container {
        width: auto 
      }
    }

    /* For smaller displays like laptops */
    @media (min-width: 768px) and (max-width: 979px) {
      .container {
        width: 724px
      }
    }

    /* For larger displays */
    @media (min-width: 1200px) {
      .container {
        width: 1170px
      }
    }
  </style>
<script src="application/js/common/jquery-2.0.2.min.js"></script>
<script src="application/js/common/jquery.slides.min.js"></script>
<script src="http://203.253.20.235:8005/socket.io/socket.io.js"></script>
<script>
  $(function(){
    setRoom();
    var curPage = 1;
    $("#slides").slidesjs({
      width: 640,
      height: 480,
      pagination: {
        active: true,
        effect:"slide"
      },
      navigation : {
        active: true,
        effect:"slide"
      },
      callback : {
        loaded: function(number){
            $(".slidesjs-previous").hide();
        },
        complete: function(number){
          curPage = number;
          if(number==1)
            $(".slidesjs-previous").hide();
          else
            $(".slidesjs-previous").show();
          if(number==<?=count($imageList)?>)
            $(".slidesjs-next").hide();
          else
            $(".slidesjs-next").show();        
        }
      }
    });
    $("#exitBtn").click(function(){
      $("#project_idx").val(project_idx);
      $("#project_name").val(project_name);
      $("#loadProject").submit();
    });

    $(document).keydown(function(e){
        switch(e.keyCode){
          case 37: // ←
          case 40: // ↓
            if(curPage>1)
              curPage-=1;
            onSlide(curPage);
            break;
          case 38: // ↑
          case 39: // →
            if(curPage < <?=count($imageList)?>)
              curPage+=1;
            onSlide(curPage);
            break;
          default:
            break;
        }
    });
  });

</script>
<script>
/*socket 연결*/
var socket = io.connect("http://203.253.20.235:8005");
var project_idx = "<?=$this->session->userdata('project_idx');?>";
var project_name = "<?=$this->session->userdata('project_name');?>";

socket.on('getSlide', function(data) {
  onSlide(data);
});

function onSlide(paging){
    $("li:nth-child("+paging+") a").click();
}

function setRoom(){
  socket.emit("setRoom", project_idx);
}

</script>

</head>
<body>
<img id="exitBtn" src="application/js/common/images/slides_exit.png" style="float:right;padding-right:30px;"/>
<div class="container" style="padding-top:60px;">
  <div id="slides">
  <?php
    if(isset($imageList)&&count($imageList)>0)
  {
    foreach($imageList as $row)
    {
  ?>
      <img src="<?=$row->canvas_img?>"/>
  <?php
    }
  }
?> 
  </div>
</div>
<form id="loadProject" action="project" method="post">
    <input type="hidden" id="project_idx" name="project_idx" />
    <input type="hidden" id="project_name" name="project_name" />
</form>

</body>
</html>