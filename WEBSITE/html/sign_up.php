<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/mainPage.css" rel="stylesheet">    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    
    <!-- // <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script> -->
     <script src="../JS/signUP.js"></script>
  </head>
  <body>
    
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container ">        
      <div id="navbar" > 
        <div class="form-group">
          <h1><span class="label navbar-left">Air Pollution Testbed</span></h1>    
          
          <form method="post" action="../index.php" class="navbar-form navbar-right">
            <input type="submit" class="btn btn-success" value="Home"></input>
          </form>  
        
         
          <form  name="login_form" method="post" action="../php/user_info/login_match.php" class="navbar-form navbar-right">
            <div class="form-group">
              <input name="user_id" type="text" placeholder="E-MAIL" class="form-control">            
              <input name="pwd" type="password" placeholder="Password" class="form-control">
              <input type="submit" class="btn btn-success " value="Sign in"></input>
            </div>                        
          </form> 
        </div>  
      </div>
      </div>
    </nav>

    
    <div  id="containterAfterNav3"> 
    </div>    


    <div class="container">
      <!-- Example row of columns -->
      <div class="row">
        
        <div class="col-md-4">
       </div>

        <div class="col-md-4">
        	<h1><span class="label label-info">SIGN UP</span></h1>
          <hr>
        	<form name="join" method="post" action="../php/user_info/sign_save.php">        		
        <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 35px;" id="basic-addon1">E-MAIL</span>
  			<input name ="user_id" type="email" class="form-control" placeholder="User-Email" aria-describedby="basic-addon1">
           </div>
           
          <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 13px;" id="basic-addon1">Last Name</span>
  			<input name ="lname" type="text" class="form-control" placeholder="Last Name" aria-describedby="basic-addon1">		
          </div>
          
          <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 12px;" id="basic-addon1">First Name</span>
  			<input name ="fname" type="text" class="form-control" placeholder="First Name" aria-describedby="basic-addon1">
          </div>
          
          <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 30px;" id="basic-addon1">Address</span>
  			<input name ="addr" type="text" class="form-control" placeholder="Address" aria-describedby="basic-addon1">
          </div>
          <hr>
        <div class="input-group"> 

  		  	 <input id ="pollution" type="radio" name="sex" value="male"> Male
  		  	 <input id ="pollution" type="radio" name="sex" value="female"> Female
           <input id ="pollution" type="radio" name="sex" value="other"> Other
			 
       </div>
          <hr>          
          <div class="input-group">
  			<span class="input-group-addon" style="padding-right: 66px;" id="basic-addon1">PW</span>
  			<input name ="pwd" type="password" class="form-control" placeholder="UserPassword" aria-describedby="basic-addon1">
          </div>
          <div class="input-group">
  			<span class="input-group-addon" id="basic-addon1">PW Confirm</span>
  			<input name ="pwd2" type="password" class="form-control" placeholder="CofrimPassword" aria-describedby="basic-addon1">
          </div>
          <input name="count" type="hidden"; value="0">
          <hr>
          <div class="input-group">
          <button type="submit" class="btn btn-success" onClick="member_save();">Submit</button>
        </div>
      </form>

        </div>
      </div>
    <div id="containterAfterNav4" >      
    </div>
      <div class="container">
      <hr>    
      <footer>      
        <p>Qualcomm Institute 2016 PhD Matteo Danieletto and PhD Seokheon Cho</p>
        <p> matteo dot danieletto at eng dot ucsd dot edu</p>
      </footer>
    </div>  
    </div> <!-- /container -->
  </body>

</html>