<?php @session_start();
$_SESSION['EMPW']=$_POST['Email'];
?>
<?php require_once('Connections/Dbconnection.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_Email = "-1";
if (isset($_SESSION['EMPW'])) {
  $colname_Email = $_SESSION['EMPW'];
}
mysql_select_db($database_Dbconnection, $Dbconnection);
$query_Email = sprintf("SELECT * FROM `user` WHERE Email = %s", GetSQLValueString($colname_Email, "text"));
$Email = mysql_query($query_Email, $Dbconnection) or die(mysql_error());
$row_Email = mysql_fetch_assoc($Email);
$totalRows_Email = mysql_num_rows($Email);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($Email);
?>
<?php
 if ($totalRows_Email>0){
$from="noreply@yourdomain.com";
$Email=$_POST['Email'];
$subject="Your Domain-Email Password";
$message="Here is your passowrd:".$row_Email['Password'];
mail($Email,$subject,$message, "From".$from);
 }
if($totalRows_Email>0){
	echo "Please check your email you have been sent your password";
}else {
	echo "Fail-Please try again";
}
		
?>
