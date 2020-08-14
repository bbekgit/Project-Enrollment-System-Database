<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
$clientid = $_GET["clientid"];
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

  $sql = "update myclient ".
         "set password = 'password' ".
         "where clientid='$clientid'";
  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];
  if ($result == false){
    display_oracle_error_message($cursor);
    die("Client Query Failed.");
  }
  echo("'$clientid' successfully reset password to 'password'.<BR />".
    "Click <A HREF=\"welcomepage.php?sessionid=$sessionid\">here</A> ".
    "here to go back to welcome page.");

}
else {
  echo("Invalid User Type. Click ".
    "<A HREF=\"welcomepage.php?sessionid=$sessionid\">here</A> to go back to Welcome Page.");
}
?>