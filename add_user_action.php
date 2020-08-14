<?
ini_set( "display_errors", 0);  

include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

$sql = "select type ".
       "from myclientsession ".
       "join myclient on myclient.clientid = myclientsession.clientid ".
       "where sessionid='$sessionid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}
$values = oci_fetch_array ($cursor);
oci_free_statement($cursor);
$type = $values[0];

if ($type == "1" or $type == "2") {

$clientid = trim($_POST["clientid"]);
if ($clientid == "") $clientid = 'NULL';

$password = $_POST["password"];
if ($password == "") $password = 'NULL';

$type = $_POST["q_type"];

if ($type == "0") {
  $typename = "student";
}
else if ($type == "1") {
  $typename = "administrator";
}
else if ($type == "2") {
  $typename = "student administrator";
}

$sql = "insert into myclient values ('$clientid', '$password', '$type', '$typename')";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  echo "<B>Insertion Failed.</B> <BR />";

  display_oracle_error_message($cursor);
  
  die("<i> 

  <form method=\"post\" action=\"dept_add?sessionid=$sessionid\">

  <input type=\"hidden\" value = \"$dnumber\" name=\"dnumber\">
  <input type=\"hidden\" value = \"$dname\" name=\"dname\">
  <input type=\"hidden\" value = \"$location\" name=\"location\">
  
  Read the error message, and then try again:
  <form method=\"post\" action=\"manage_user.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>

  </i>
  ");
}

Header("Location:manage_user.php?sessionid=$sessionid");

}
else {
  echo("Invalid User Type. Click ".
    "<A HREF=\"welcomepage.php?sessionid=$sessionid\">here</A> to go back to Welcome Page.");
}

?>