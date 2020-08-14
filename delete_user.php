<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
$q_clientid = $_GET["clientid"];
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

$sql = "select clientid, password, type, typename from myclient where clientid = '$q_clientid'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){ 
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

if (!($values = oci_fetch_array ($cursor))) {
  Header("Location:manage_user.php?sessionid=$sessionid");
}
oci_free_statement($cursor);

$clientid = $values[0];
$password = $values[1];
$type = $values[2];
$typename = $values[3];

echo("
  <form method=\"post\" action=\"delete_user_action.php?sessionid=$sessionid\">
  Client ID (Read-only): <input type=\"text\" readonly value = \"$clientid\" size=\"8\" maxlength=\"8\" name=\"clientid\"> <br /> 
  Password: <input type=\"password\" disabled value = \"$password\" size=\"12\" maxlength=\"12\" name=\"password\">  <br />
  User Type: <input type=\"text\" disabled value = \"$type\" size=\"1\" maxlength=\"1\" name=\"type\">  <br />
  Type Name: <input type=\"text\" disabled value = \"$typename\" size=\"14\" maxlength=\"14\" name=\"typename\">  <br />
  <input type=\"submit\" value=\"Delete\">
  </form>

  <form method=\"post\" action=\"manage_user.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>
  ");

}
else {
  echo("Invalid User Type. Click ".
    "<A HREF=\"welcomepage.php?sessionid=$sessionid\">here</A> to go back to Welcome Page.");
}

?>