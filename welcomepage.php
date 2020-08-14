<?
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

echo("Data Management Menu: <br />");
if ($type == "0") { 
  echo("Student User!<br />");
}
else if ($type == "1" or $type == "2") {
  echo("Administrative User!<br />");
  echo("<A href='manage_user.php?sessionid=$sessionid'>Manage User</A><br />");
}
echo("<A href='resetpassword.php?sessionid=$sessionid'>Reset My Password</A><br />");

echo("<br />");
echo("<br />");
echo("<A HREF = \"logout_action.php?sessionid=$sessionid\">Logout</A>");
?>