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
  <form method=\"post\" action=\"manage_user.php?sessionid=$sessionid\">
  Client ID: <input type=\"text\" size=\"8\" maxlength=\"8\" name=\"q_clientid\"> 
  ");

$sql = "select type, typename from myclient order by type";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Query Failed.");
}

echo("
  <BR />
  User Type:
  <select name=\"q_type\">
  <option value=\"\">All</option>
  ");

while ($values = oci_fetch_array ($cursor)){
  $type = $values[0];
  $typename = $values[1];
  echo("
    <option value=\"$type\">$type, $typename</option>
    ");
}
oci_free_statement($cursor);

echo("
  </select>
  <input type=\"submit\" value=\"Search\">
  </form>

  <form method=\"post\" action=\"welcomepage.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Go Back\">
  </form>

  <form method=\"post\" action=\"add_user.php?sessionid=$sessionid\">
  <input type=\"submit\" value=\"Add A New User\">
  </form>
  ");

$q_clientid = $_POST["q_clientid"];
$q_type = $_POST["q_type"];

$whereClause = " 1=1 ";

if (isset($q_clientid) and trim($q_clientid) != "") { 
  $whereClause .= " and clientid like '%$q_clientid%'"; 
}

if (isset($q_type) and $q_type != "") { 
  $whereClause .= " and type = $q_type"; 
}

$sql = "select clientid, type, typename
  from myclient where $whereClause order by clientid";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

echo "<table border=1>";
echo "<tr> <th>Client ID</th> <th>User Type</th> <th>Type Name</th> <th>Reset Password</th> <th>Delete</th></tr>";

while ($values = oci_fetch_array ($cursor)){
  $clientid = $values[0];
  $type = $values[1];
  $typename = $values[2];
  echo("<tr>" . 
    "<td>$clientid</td> <td>$type</td> <td>$typename</td>".
    " <td> <A HREF=\"reset_user.php?sessionid=$sessionid&clientid=$clientid\">Reset Password</A> </td> ".
    " <td> <A HREF=\"delete_user.php?sessionid=$sessionid&clientid=$clientid\">Delete</A> </td> ".
    "</tr>");
}
oci_free_statement($cursor);

echo "</table>";

}
else {
  echo("Invalid User Type. Click ".
    "<A HREF=\"welcomepage.php?sessionid=$sessionid\">here</A> to go back to Welcome Page.");
}

?>