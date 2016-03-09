<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change User info</title>


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
          <h1><span class="label label-info">INFO Change</span></h1>
        	<form name="join" method="post" action="../php/user_info/changeinfo_update.php">
            <div class="input-group">
  			
           </div>
          <div class="input-group">
  			
          </div>
          <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 22px;" id="basic-addon1">ADDRESS</span>
  			<input name ="addr" type="text" class="form-control" placeholder="ADDRESS" aria-describedby="basic-addon1">
          </div>
          
          
          <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 66px;" id="basic-addon1">PW</span>
  			<input name ="pwd" type="password" class="form-control" placeholder="UserPassword" aria-describedby="basic-addon1">
          </div>
          <div class="input-group">
  			<span class="input-group-addon" id="basic-addon1">Confirm PW</span>
  			<input name ="pwd2" type="password" class="form-control" placeholder="CofrimPassword" aria-describedby="basic-addon1">
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
function member_save()
{
    // 6.form 을 f 에 지정
    var f = document.join;


    if(f.pwd.value == ""){
        alert("Please Insert PW");
        
        return false;
    }
    
    if(f.school.value == ""){
        alert("Please Insert School Name");
        
        return false;
    }
    
    if(f.tel.value == ""){
        alert("Please Insert Tel Num");
        
        return false;
    }

    if(f.pwd.value != f.pwd2.value){
        // 9.비밀번호와 확인이 서로 다르면 경고창으로 메세지 출력 후 함수 종료
        alert("Please Confirm PW");
        
        return false;
    }

    // 10.검사가 성공이면 form 을 submit 한다
    f.submit();

}
</script>
</html>