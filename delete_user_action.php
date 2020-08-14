<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
$clientid = $_POST["clientid"];
verify_session($sessionid);

ini_set( "display_errors", 0);  

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

$sql = "delete from myclient where clientid = '$clientid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){ 
  echo "<B>Deletion Failed.</B> <BR />";

  display_oracle_error_message($cursor);

  die("<i> 

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