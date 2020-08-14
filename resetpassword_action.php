<?
include "utility_functions.php";

$sessionid =$_GET["sessionid"];
verify_session($sessionid);

$q_cpassword= $_POST["q_cpassword"];
$q_npassword= $_POST["q_npassword"];

$sql = "select myclient.clientid ".
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
$clientid = $values[0];
$sql = "select password ".
       "from myclient ".
       "where clientid='$clientid'";
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}
$values = oci_fetch_array ($cursor);
oci_free_statement($cursor);
$cpassword = $values[0];

if (isset($q_cpassword) and trim($q_cpassword)!= "") { 
  if (isset($q_npassword) and trim($q_npassword)!= "") { 
    if ($q_cpassword == $cpassword) {
      $sql = "update myclient ".
             "set password = '$q_npassword' ".
             "where clientid='$clientid'";
      $result_array = execute_sql_in_oracle ($sql);
      $result = $result_array["flag"];
      $cursor = $result_array["cursor"];
      if ($result == false){
        display_oracle_error_message($cursor);
        die("Client Query Failed.");
      }
      echo("Update Successful.<BR />".
        "Click <A HREF=\"welcomepage.php?sessionid=$sessionid\">here</A> ".
        "here to go back to welcome page.");
    }
    else {
      echo("The Current Password is incorrect. Click ".
        "<A HREF=\"resetpassword.php?sessionid=$sessionid\">here</A> to go back.");
    }
  }
  else {
    echo("Invalid entry for New Password. Click ".
      "<A HREF=\"resetpassword.php?sessionid=$sessionid\">here</A> to go back.");
  }
}
else {
  echo("Invalid entry for Current Password. Click ".
    "<A HREF=\"resetpassword.php?sessionid=$sessionid\">here</A> to go back.");
}

?>