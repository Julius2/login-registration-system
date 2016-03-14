<?php @session_start() ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "UpdateForm")) {
  $updateSQL = sprintf("UPDATE ``user`` SET Email=%s, Password=%s WHERE User_id=%s",
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['UserIdhiddenField'], "int"));

  mysql_select_db($database_Dbconnection, $Dbconnection);
  $Result1 = mysql_query($updateSQL, $Dbconnection) or die(mysql_error());

  $updateGoTo = "login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_User = "-1";
if (isset($_SESSION['MM_UserName'])) {
  $colname_User = $_SESSION['MM_UserName'];
}
mysql_select_db($database_Dbconnection, $Dbconnection);
$query_User = sprintf("SELECT * FROM `user` WHERE UserName = %s", GetSQLValueString($colname_User, "text"));
$User = mysql_query($query_User, $Dbconnection) or die(mysql_error());
$row_User = mysql_fetch_assoc($User);
$totalRows_User = mysql_num_rows($User);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="CSS/layout.css" rel="stylesheet" type="text/css"/>
<link href="CSS/menu.css" rel="stylesheet" type="text/css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
</head>
<body> 
<div id="Holder"></div>
<div id="Header"></div>
<div id="Navbar">
 <nav>
      <ul>
          <li><a href="#">Login</a></li>
          <li><a href="#">Register</a></li>
          <li><a href="#">Forgot Password</a></li>
       </ul>
  </nav>
</div>
<div id="Content">
    <div id="PageHeading">
      <h1>Update Account</h1>
</div>
	<div id="ContentLeft">
	  <h2>Account Links</h2>
	  <h6>Your message</h6>
  </div>
    <div id="ContentRight">
      <form id="UpdateForm" name="UpdateForm" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="600" border="0" align="center">
          <tr>
            <td><h6>Account<?php echo $row_User['FirstName']; ?><?php echo $row_User['LastName']; ?><?php echo $row_User['Email']; ?>|Username:<?php echo $row_User['UserName']; ?></h6></td>
          </tr>
        </table>
        <table width="400" border="0" align="center">
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><h6>Email
              </h6>
              <h6><span class="StyleTextField" id="sprytextfield1">
                <label for="Email"></label>
                <input name="Email" type="text" class="StyleTextField" id="Email" value="<?php echo $row_User['Email']; ?>" />
              </span></h6>
            <span><span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td><h6>Password</h6></td>
          </tr>
          <tr>
            <td><h6><span class="StyleTextField" id="sprypassword1">
              <label for="Password"></label>
              <br />
              <input name="Password" type="password" class="StyleTextField" id="Password" value="<?php echo $row_User['Password']; ?>" />
            </span></h6>
            <span><span class="passwordRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><input name="UpdateButton" type="submit" class="StyleTextField" id="UpdateButton" value="Update Account" />
            <input name="UserIdhiddenField" type="hidden" id="UserIdhiddenField" value="<?php echo $row_User['User_id']; ?>" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
        <p>&nbsp;</p>
        <input type="hidden" name="MM_update" value="UpdateForm" />
      </form>
    </div>
</div>
<div id="Footer"></div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
</script>
</body>
</html>
<?php
mysql_free_result($User);
?>
