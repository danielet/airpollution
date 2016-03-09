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
          <h1><span class="label label-info">PW Change</span></h1>
        	<form name="join" method="post" action="../php/user_info/findinfo_changepw.php">
            <div class="input-group">
  			
           </div>
          <div class="input-group">
  			
          </div>
          <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 41px;" id="basic-addon1">E-MAIL</span>
  			<input name ="user_id" type="text" class="form-control" placeholder="User-Email" aria-describedby="basic-addon1">
          </div>
          
          <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 66px;" id="basic-addon1">PW</span>
  			<input name ="pwd" type="password" class="form-control" placeholder="UserPassword" aria-describedby="basic-addon1">
          </div>
          
          <div class="input-group">
  			<span class="input-group-addon" id="basic-addon1">Confirm PW</span>
  			<input name ="pwd2" type="password" class="form-control" placeholder="CofrimPassword" aria-describedby="basic-addon1">
          </div>
          <hr>
           <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 26px;" id="basic-addon1">CONFIRM NUMBER</span>
  			<input name ="confirm" type="password" class="form-control" placeholder="NUMBER" aria-describedby="basic-addon1">
         </div>
         
          <br>
          <br>
          <button type="submit" class="btn btn-success" onClick="member_save();">Change</button>
         </form>
       </div>
        <div class="col-md-4">
          
        </div>
      </div>

      <hr>

      <footer>
        <p>&copy; Company 2014</p>
      </footer>
    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
  </body>
  	<script>
// 5.입력필드 검사함수
function member_save()
{
    // 6.form 을 f 에 지정
    var f = document.join;


    if(f.pwd.value == ""){
        alert("비밀번호를 입력해 주세요.");
        
        return false;
    }

    if(f.pwd.value != f.pwd2.value){
        // 9.비밀번호와 확인이 서로 다르면 경고창으로 메세지 출력 후 함수 종료
        alert("비밀번호를 확인해 주세요.");
        
        return false;
    }

    // 10.검사가 성공이면 form 을 submit 한다
    f.submit();

}
</script>
</html>