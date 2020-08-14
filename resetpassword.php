<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

echo("
  <form method=\"post\" action=\"resetpassword_action.php?sessionid=$sessionid\">
  Current Password: <input type=\"password\" size=\"12\" maxlength=\"12\" name=\"q_cpassword\"><br />  
  New Password: <input type=\"password\" size=\"12\" maxlength=\"12\" name=\"q_npassword\"><br />  
  <input type=\"submit\" value=\"Reset\">
  </form>

  <form method=\"post\" action=\"welcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");

?>