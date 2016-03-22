<!DOCTYPE html>

<?php
include_once '../php/include.php';

sec_session_start();
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change User info</title>


    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/googleMapsCSS.css" rel="stylesheet">
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></scrip
    t>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript" src="../js/changeInfo.js"></script>     
  </head>
  <body>
    <?php
  
    if(login_check()!= true)
    {
      header('Location: http://airpollution.calit2.net/WEBSITE/');
    }
  ?>

  <div id="navbar" class="navbar-collapse collapse">
    <h1><span class="label navbar-left">Air Pollution Testbed</span></h1> 
  <form name="login_form" method="post" action="./login_match.php" class="navbar-form navbar-right">
  <div class="form-group">    
  <a href="../php/user_info/logout.php"><input name="logout" value="Logout"type="button" placeholder="ID" class="btn btn-success"></a>
  <a href="./googlemap.php"><button type="button" class="btn btn-success">Map</button></a>                      
  <a href="../index.php"><button type="button" class="btn btn-success">Home</button></a>                      
  </div>
  </form>
</div>
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
    
    </div> <!-- /container -->
    <div id="containterAfterNav2" >      
    </div>
<div class="container">
      <hr>    
      <footer>      
        <p>Qualcomm Institute 2016 PhD Matteo Danieletto and PhD Seokheon Cho</p>
        <p> matteo dot danieletto at eng dot ucsd dot edu</p>
      </footer>
    </div>  

    
  </body>
</html>