<?include("../include.php");

if($_SESSION[user_id]){ //confirm user id existence. if exist user id,go to before page.
    
 ?>
 	<meta charset="utf-8">
    <script>
        alert("present login");
        location.replace("../../html/Main.php");
    </script>
    <?
}

if(trim($_POST[user_id]) == ""){ //if not input id,back to before page
    ?>
    <script>
        alert("insert email");
        history.back();
    </script>
    <?
    exit;
    
}

if($_POST[name] == ""){ //if not input name,back to before page
    ?>
    <script>
        alert("insert name");
        history.back();
    </script>
    <?
    exit;
    
}


$confirm_code=rand(100,999); // create password find code.
$chk_sql = "select * from user where user_id = '".trim($_POST[user_id])."'"; //existence confirm input id 
$chk_result = sql_query($chk_sql); //execute sql
$chk_data = mysql_fetch_array($chk_result); // save database date in array
$sql = "update user set confirmcode='".$confirm_code."' where user_id = '".trim($chk_data[user_id])."'";   // confirm code save in database.
     sql_query($sql);

if($chk_data[user_id]){ //if existence user id
    if(trim($_POST[name]) == $chk_data[fname].$chk_data[lname]){ //confirm to same. input name with database name.

    
$smtp_mail_id = "wnsdlek2@naver.com"; // send email address
$smtp_mail_pw = "ghwnsghwns7262"; // email password
$to_email = "$_POST[user_id]"; // receive email address
$to_name = "HOJUN"; // receive person name
$title = "TEST"; // email title
$from_name = "ADMIN"; // send person name
$from_email = "wnsdlek2@naver.com"; // send person email address.
$content = "confirm code = $confirm_code"; // input Contents 
 
$smtp_use = 'smtp.naver.com'; //user used naver mail
//$smtp_use = 'smtp.gmail.com'; //if use google mail.
if ($smtp_use == 'smtp.naver.com') { 
$from_email = $smtp_mail_id; // only send email address possible
}else {
 $from_email = $from_email; 
}
 

include("class.phpmailer.php"); // include function. mailer
include("class.smtp.php"); // include function. mailer

   
$mail = new PHPMailer(true);
$mail->ContentType="text/html";
$mail->Charset = "utf-8";
$mail->Encoding = "base64";
$mail->IsSMTP();

try {
  $mail->Host = $smtp_use;   // email server.
  $mail->SMTPAuth = true;          // SMTP certification.
  $mail->Port = 465;            // email port num.
  $mail->SMTPSecure = "ssl";        // use to SSL.
  $mail->Username   = $smtp_mail_id; // send person email address.
  $mail->Password   = $smtp_mail_pw; // send person email password.
  $mail->SetFrom($from_email, $from_name); // send person email address.
  $mail->AddAddress($to_email, $to_name);  // receive person email address.
  $mail->Subject = $title;         // mail title
  $mail->MsgHTML($content);         // 메mail contents
  $mail->Send();             // send email.



} catch (phpmailerException $e) {
  echo "ok".$e->errorMessage(); // alert send email complete 

} catch (Exception $e) {
  echo "no".$e->getMessage(); // alert don't send email.

}
?><script>
       location.replace("../../html/findinfo_codeinput.php");   
       </script>
       <?
    }else{ // not matched name.
        ?>
        <script>
            alert("Don't match name");
            history.back();
        </script>
        <?
        exit;
    }
 }else{ // net matched email.   
    ?>
    <script>
        alert("Don't match email");
        history.back;
    </script>
    <?
    exit;
}
?>
