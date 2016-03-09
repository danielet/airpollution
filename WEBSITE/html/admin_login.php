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
          <a href="./Main.php"><input type="image" style="width:150px; height:50px;" src="../img/QI1.png"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <form name="login_form" method="post" action="../php/login_match.php" class="navbar-form navbar-right">
            <div class="form-group">
             <a href="../php/user_info/logout.php"><input name="logout" value="logout"type="button" placeholder="ID" class="btn btn-success"></a>
            </div>
            
            <a href="./changeinfo.php"><button type="button" class="btn btn-success">Modify</button></a>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
  

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        <div class="col-md-4">
          
        </div>
        <div class="col-md-4">
        	<br>
        	<br>
        	<br>
        	<br>
          <h1><span class="label label-info">Admin Login</span></h1>
          <br>
          <br>
          <br>
        	<form name="join" method="post" action="../php/user_info/admin_confirm.php">
        	<div class="input-group">
  			<span class="input-group-addon" style="padding-right: 6px;" id="basic-addon1">Admin E-mail</span>
  			<input name ="user_id" type="text" class="form-control" placeholder="E-mail" aria-describedby="basic-addon1">			
          </div>
            <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 12px;" id="basic-addon1">Admin Code</span>
  			<input name ="pwd" type="password" class="form-control" placeholder="Password" aria-describedby="basic-addon1">			
          </div>
          <br>
          <br>
          <button type="submit" class="btn btn-success">Confirm</button>
           </form>
       </div>
        <div class="col-md-4">
          
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Qualcomm Institute 2016</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../../dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
  	<script>
// 5.입력필드 검사함수

</script>
</html>