<?include("../php/include.php");

if($_SESSION["user_id"]){
    
 ?>
 	<meta charset="utf-8">
    <script>
        
        location.replace("Main.php");

    </script>
    <?

}?>
<!DOCTYPE html>
<html lang="ko">
  <head>
  	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="http://googledrive.com/host/0B-QKv6rUoIcGeHd6VV9JczlHUjg"></script><!-- holder.js link -->
	<script>
    $(document).ready(function() {
    $('#myCarousel').carousel('cycle');
        
    });
</script> <!--이미지 슬라이드-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 위 3개의 메타 태그는 *반드시* head 태그의 처음에 와야합니다; 어떤 다른 콘텐츠들은 반드시 이 태그들 *다음에* 와야 합니다 -->
    <title>QI</title>

    <!-- 부트스트랩 -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- IE8 에서 HTML5 요소와 미디어 쿼리를 위한 HTML5 shim 와 Respond.js -->
    <!-- WARNING: Respond.js 는 당신이 file:// 을 통해 페이지를 볼 때는 동작하지 않습니다. -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="./login.php"><input type="image" style="width:150px; height:55px;" src="../img/QI1.PNG"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form name="login_form" method="post" action="../php/user_info/login_match.php" class="navbar-form navbar-right">
            <div class="form-group">
              <input name="user_id" type="text" placeholder="E-MAIL" class="form-control">
            </div>
            <div class="form-group">
              <input name="pwd" type="password" placeholder="Password" class="form-control">
            </div>
            <div class="form-group">              
            <button type="submit" class="btn btn-success">Sign in</button>
            <a href="./sign_up.php"><button type="button" class="btn btn-success">Sign up</button></a>
            <a href="./findinfo.php"><button type="button" class="btn btn-success">FIND INFO</button></a>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <br>
    <br>
    <br>
   
    <div class="container">
   <!-- <h1><img src="../img/heart.jpg" style="width:40px; height:40px;">We will protect your surroundings.<img src="../img/heart.jpg" style="width:40px; height:40px;"></h1>-->  <!---picture title--->
    <div>
      <div class="container">      		
	<div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="4000">
	  <ol class="carousel-indicators">
	    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
	    <li data-target="#myCarousel" data-slide-to="1" class=""></li>
	    <li data-target="#myCarousel" data-slide-to="2" class=""></li>
	  </ol>
	  <div class="carousel-inner" role="listbox">
	    <div class="item active">
	      <img src="../img/ucsd1.jpg" style="width:1200px; height:500px;">
	    </div>
	    <div class="item">
	      <img src="../img/18.png" style="width:1200px; height:500px;">
	    </div>
	    <div class="item">
	      <img src="../img/main2.jpg" style="width:1200px; height:500px;">
	    </div>
	  </div>
	  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
	    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	    <span class="sr-only">Previous</span>
	  </a>
	  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
	    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	    <span class="sr-only">Next</span>
	  </a>
	
	</div>
	</div>

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          <h2>ForeCast</h2>
          <p></p>
          <img src="../img/forecast.png" style='width:700px; height:200px;' class="img-rounded">
        </div>
        <div class="col-md-4">
          <h2></h2>
          <p></p>
          
       </div>
        <div class="col-md-4">
          <h2>Location</h2>
          <p></p>
          <img src="../img/map.png" style='width:300px; height:200px;' class="img-rounded">
        </div>
      </div>

      <hr>

      <footer>
        
        <span style="font-family:궁서; font-size:5px;"><center>address : 9500 Gilman Dr, La Jolla, CA 92093, United States.<br>
        		Tel. 1(213)-3787796 Fax. 1(714)-2514007<br>
        		E-Mail. specialjuni@gamil.com 
                Company Registration Number. 1214-0430-0321-1226<br>
                QI Korea Internship IOT trakking Project</center></span>
                <img src="../img/qibottom.jpg" style='margin-left:1000px; width:150px; height:100px;' class="img-rounded">
                <br>
                <br>
                
                  <img src="../img/mini1.jpg" style='width:51px; height:28px;' class="img-rounded">
                 <img src="../img/mini2.png" style='width:33px; height:26px;' class="img-rounded">
                 <img src="../img/mini3.png" style='width:33px; height:26px;' class="img-rounded">
                 
                <p>&copy; Qualcomm Institute 2016</p> 
               
      </footer>
    </div> <!-- /container -->


   
  </body>
</html>