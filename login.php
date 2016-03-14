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

mysql_select_db($database_Dbconnection, $Dbconnection);
$query_login = "SELECT * FROM `user`";
$login = mysql_query($query_login, $Dbconnection) or die(mysql_error());
$row_login = mysql_fetch_assoc($login);
$totalRows_login = mysql_num_rows($login);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['UsernName'])) {
  $loginUsername=$_POST['UsernName'];
  $password=$_POST['Password'];
  $MM_fldUserAuthorization = "UserLevel";
  $MM_redirectLoginSuccess = "Account.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = true;
  mysql_select_db($database_Dbconnection, $Dbconnection);
  	
  $LoginRS__query=sprintf("SELECT UserName, Password, UserLevel FROM `user` WHERE UserName=%s AND Password=%s",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $Dbconnection) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'UserLevel');
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && true) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="CSS/layout.css" rel="stylesheet" type="text/css"/>
<link href="CSS/menu.css" rel="stylesheet" type="text/css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>login</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="Holder"></div>
<div id="Header"></div>
<div id="Navbar">
 <nav>
      <ul>
          <li><a href="login.php">Login</a></li>
          <li><a href="Register.php">Register</a></li>
          <li><a href="ForgotPassword.php">Forgot Password</a></li>
    </ul>
  </nav>
</div>
<div id="Content">
    <div id="PageHeading">
      <h1>Login!</h1>
  </div>
	<div id="ContentLeft">
	  <h2>&nbsp;</h2>
	  <h6>Your message</h6>
  </div>
    <div id="ContentRight">
      <form id="login" name="login" method="POST" action="<?php echo $loginFormAction; ?>">
        <table width="400" border="0" align="center">
          <tr>
            <td><h6>UserName</h6>
              <span id="sprytextfield1">
              <label for="UsernName"></label>
              <input name="UsernName" type="text" class="StyleTextField" id="UsernName" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><h6>Password</h6>
              <span id="sprytextfield2">
              <label for="Password"></label>
              <input name="Password" type="text" class="StyleTextField" id="Password" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><input name="Login" type="submit" class="StyleTextField" id="Login" value="Login" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
      </form>
  </div>
</div>
<div id="Footer">Your name <a href="Admin.php">Admin</a></div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
</script>
</body>
</html>
<?php
mysql_free_result($login);
?>
