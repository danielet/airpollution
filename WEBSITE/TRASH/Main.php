<?
include ("../php/include.php");
if(!$_SESSION[user_id]){
		?>
		<script>
		alert("please login");
		location.replace("login.php");
		</script>
		<?
	}
?>
<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 위 3개의 메타 태그는 *반드시* head 태그의 처음에 와야합니다; 어떤 다른 콘텐츠들은 반드시 이 태그들 *다음에* 와야 합니다 -->
    <title>QI</title>

    <!-- 부트스트랩 -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    
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
          <a href="./login.php"><input type="image" style="width:150px; height:50px;" src="../img/QI1.PNG"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form name="login_form" method="post" action="../php/user_info/login_match.php" class="navbar-form navbar-right">
            <div class="form-group">
             <a href="../php/user_info/logout.php"><input name="logout" value="logout"type="button" placeholder="ID" class="btn btn-success"></a>
            </div>
            
            <a href="./changeinfo.php"><button type="button" class="btn btn-success">Modify</button></a>
            
            
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>
    <!-- Main jumbotron for a primary marketing message or call to action -->
    	<br>
    	<br>
    	<br>
    	
         <div class="container">
      	<div  style="position: relative; z-index: '2';"><img src="../img/main3.png"  style='width:1150px; height:400px;'/></div>
      	<div  style="position: relative; z-index: '1'; left:10px; top: -200px;">
      		
        <br>
        <br>
        <p><a class="btn btn-primary btn-lg" href="./googlemap.php" role="button">Show my Surroundings &raquo;</a></p>
      
      </div>
      </div>
<!--
    <div class="container">
      
      <div class="row">
        <div style="top: -100px;" class="col-md-4">
        </div>
        <div style="top: -100px;" class="col-md-4">
        	<h2>To increase USER!</h2>
          <img src="../img/user1.jpg" style='width:400px; height:150px;' class="img-rounded">
       </div>
        <div style="top: -100px;" class="col-md-4">
        	<br>
         <img src="../img/health.png" style='width:400px; height:200px;' class="img-rounded">        
        </div>
      </div>
      <hr>
       <footer>
        
        <span style="font-family:궁서; font-size:5px;"><center>address : 9500 Gilman Dr, La Jolla, CA 92093, United States.<br>
        		Tel. 1(213)-3787796 Fax. 1(714)-2514007<br>
        		E-Mail. specialjuni@gamil.com 
                Company Registration Number. 1214-0430-0321-1226<br>
                QI Korea Internship IOT trakking Project</center></span>
                <br>
                <br>
                 <img src="../img/qibottom.jpg" style='margin-left:1000px; width:150px; height:100px;' class="img-rounded">
                  <img src="../img/mini1.jpg" style='width:51px; height:28px;' class="img-rounded">
                 <img src="../img/mini2.png" style='width:33px; height:26px;' class="img-rounded">
                 <img src="../img/mini3.png" style='width:33px; height:26px;' class="img-rounded">
                <p>&copy; Qualcomm Institute 2016</p>
      </footer>
    </div> 
-->
    <!-- /container -->


 
  </body>
</html>