<?
include("./php/include.php");
?>

<!DOCTYPE html>
<html >
  <head>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="http://googledrive.com/host/0B-QKv6rUoIcGeHd6VV9JczlHUjg"></script><!-- holder.js link -->

<!-- <script src="./js/weather.js"></script>     -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Airpollution QI</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">  
    <link href="./css/mainPage.css" rel="stylesheet">  
    
  </head>
  
  <body>
      
      
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container ">        
        <div id="navbar" > 
 <div class="form-group">
        <form method="post" action="./html/sign_up.php" class="navbar-form navbar-left">
          <input type="submit" class="btn btn-success" value="Sign up"></input>
        </form>   
        </div>
        <div class="form-group">
          <form method="post" action="./html/findinfo.php" target= "_blank" class="navbar-form navbar-left">
          <input type="submit" class="btn btn-success" value="Find Info"></input>
          </form> 
          </div>  
          <form  name="login_form" method="post" action="./php/user_info/login_match.php" class="navbar-form navbar-right">
            <div class="form-group">
              <input name="user_id" type="text" placeholder="E-MAIL" class="form-control">
            <!-- </div>
            <div class="form-group"> -->
              <input name="pwd" type="password" placeholder="Password" class="form-control">
            <input type="submit" class="btn btn-success " value="Sign in"></input>
            </div>                        
          </form>          
          <!-- <a href="./html/findinfo.php"><input type="button" class="btn btn-success" value="Find Info"></input></a> -->
        </div>
      </div>
    </nav>

<div  id="containterAfterNav"> </div>    
<div class="container">
  <center><h2>Air Pollution Testbed @ University of California San Diego</h2></center>
    <div class="jumbotron">
      <div class="container">        
         <div class="col-md-7">
        <img src="./img/ucsd3.jpg" class="img-rounded"></img>
         </div>
         <div class="col-md-5" >
          <p sytle="text-align: justify;">Air pollution is the introduction of particulates, biological molecules, or other harmful materials into Earth's atmosphere, 
            causing diseases, death to humans, damage to other living organisms such as animals and food crops, 
            or the natural or built environment. Air pollution may come from anthropogenic or natural sources.</p>
        </div>

      </div>      
    </div>
    </div> 
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

