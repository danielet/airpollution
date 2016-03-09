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

    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
      	<br>
      	<br>
       <h1><span class="label label-success">User Data</span></h1>
       <br>
        	<?


    mysql_query("set names euckr");
    $query = "SELECT user_id,hashed_pw,lname,fname,sex,addr,reg_date,login_count FROM user";
    $result = mysql_query($query);
	
    echo "<table border = 1px class='table table-striped'>";
    echo ("<tr>");
		echo ("<th> User_ID </th>");
		echo ("<th> Hashed_PW </th>");
		echo ("<th> Last Name </th>");
		echo ("<th> First Name </th>");
		echo ("<th> SEX </th>");
		echo ("<th> Address </th>");
		echo ("<th> Reg_Date </th>");
		echo ("<th> Login_Count </th>");
		echo ("</tr>");
		echo ("<tr>");
    while($arr = mysql_fetch_array($result)){
       
        foreach($arr as $key =>$value){
            if(!(is_int($key)||$key == "password"||$value == NULL))
                echo ("<td> $value </td>");
        }
        echo ("</tr>");
    }
    echo "</table>" 
?>
          
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
</html>