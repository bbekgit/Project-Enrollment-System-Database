<?
include "utility_functions.php";

$clientid = $_POST["clientid"];
$password = $_POST["password"];

$sql = "select clientid " .
       "from myclient " .
       "where clientid='$clientid'
         and password ='$password'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}

if($values = oci_fetch_array ($cursor)){
  oci_free_statement($cursor);

  $clientid = $values[0];

  $sessionid = md5(uniqid(rand()));

  $sql = "insert into myclientsession " .
    "(sessionid, clientid, sessiondate) " .
    "values ('$sessionid', '$clientid', sysdate)";

  $result_array = execute_sql_in_oracle ($sql);
  $result = $result_array["flag"];
  $cursor = $result_array["cursor"];

  if ($result == false){
    display_oracle_error_message($cursor);
    die("Failed to create a new session");
  }
  else {
    header("Location:welcomepage.php?sessionid=$sessionid");
  }
}
else { 
  die ('Login failed.  Click <A href="login.html">here</A> to go back to the login page.');
} 
?>