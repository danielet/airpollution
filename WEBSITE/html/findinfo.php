<!DOCTYPE html>
<html >
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 위 3개의 메타 태그는 *반드시* head 태그의 처음에 와야합니다; 어떤 다른 콘텐츠들은 반드시 이 태그들 *다음에* 와야 합니다 -->
    <title<!DOCTYPE html>
<html lang="ko">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <!-- 위 3개의 메타 태그는 *반드시* head 태그의 처음에 와야합니다; 어떤 다른 콘텐츠들은 반드시 이 태그들 *다음에* 와야 합니다 -->
    <title>QI</title>

    <!-- 부트스트랩 -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
    	#quick{
    		position : relative;
    		left:755px;
    		top:-176px;
    		width:176px;
    		height:330px;
    		z-index:1;
    	}
    </style>
    
  </head>
  
  <script>

  	
  </script>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <!-- <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div> -->

        <div id="navbar" class="navbar-collapse collapse">
          <form name="login_form" method="post" action="../php/user_info/login_match.php" class="navbar-form navbar-right">
            <div class="form-group">
              <input name="user_id" type="text" placeholder="email" class="form-control">
            </div>
            <div class="form-group">
              <input name="pwd" type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>            
          <a href="./sign_up.php"><button type="button" class="btn btn-success">Sign up</button></a>
          <a href="../index.php"><button type="button" class="btn btn-success">Home</button></a>
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
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="col-md-4">
         
          
       </div>
        <div class="col-md-4">
        	<h1><span class="label label-info">FIND INFO</span></h1>
        	<form id="join" name="join" method="post" action="../php/mailing/sendinfo_email.php">
        		
            <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 41px;" id="basic-addon1">E-MAIL</span>
  			<input id="user_id" name ="user_id" type="text" class="form-control" placeholder="User-Email" aria-describedby="basic-addon1">
           </div>
           
          <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 50px;" id="basic-addon1">Name</span>
  			<input id="name"  name ="name" type="text" class="form-control" placeholder="Name" aria-describedby="basic-addon1">		
          </div>                           
          <hr>        
          <input name="count" type="hidden"; value="0">
          <br>
          <br>
          
         <!-- <button id="sendemail" type="button" class="btn btn-success">Recieve Email</button> -->
          <a href="#" onclick="location.href = '../php/mailing/confirm_code_match.php?user_id='+document.join.user_id.value+'&name='+document.join.name.value+'&confirm='+document.join.confirm.value">
          <input type="submit" value="Send Email" class="btn btn-success"></a>
          </form>
        </div>
      </div>


      <div id="message"></div>   
      <hr>    
      <footer>
        <p>&copy; Qualcomm Institute 2016</p>
      </footer>
    </div> 
  </body>

  <script>
function member_save()
{
    
    var f = document.join;
    // 7.입력폼 검사
    if(f.email.value == ""){
        // 8.값이 없으면 경고창으로 메세지 출력 후 함수 종료
          
        return false;
    }

    if(f.name.value == ""){
      
        
        return false;
    }
    
    f.submit();

}
</script>
</html>