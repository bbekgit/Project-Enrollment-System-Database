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

if ($type == "1" or $type == "2") {

echo("
  <form method=\"post\" action=\"add_user_action.php?sessionid=$sessionid\">
  Client ID (Required): <input type=\"text\" value = \"$clientid\" size=\"8\" maxlength=\"8\" name=\"clientid\"> <br /> 
  Password (Required): <input type=\"password\" value = \"$password\" size=\"12\" maxlength=\"12\" name=\"password\">  <br />
  ");

echo("
  User Type:
  <select name=\"q_type\">
  <option value=0>0, student</option>
  <option value=1>1, administrator</option>
  <option value=2>2, student administrator</option>
  ");

echo("
  </select>
  <br />
  <input type=\"submit\" value=\"Add\">
  <input type=\"reset\" value=\"Reset to Original Value\">
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