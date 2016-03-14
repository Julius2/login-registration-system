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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="Register.php";
  $loginUsername = $_POST['UserName'];
  $LoginRS__query = sprintf("SELECT UserName FROM `user` WHERE UserName=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_Dbconnection, $Dbconnection);
  $LoginRS=mysql_query($LoginRS__query, $Dbconnection) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "RegisterForm")) {
  $insertSQL = sprintf("INSERT INTO `user` (FirstName, LastName, Email, UserName, Password) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['FName'], "text"),
                       GetSQLValueString($_POST['LName'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['UserName'], "text"),
                       GetSQLValueString($_POST['Password'], "text"));

  mysql_select_db($database_Dbconnection, $Dbconnection);
  $Result1 = mysql_query($insertSQL, $Dbconnection) or die(mysql_error());

  $insertGoTo = "login.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_Dbconnection, $Dbconnection);
$query_Register = "SELECT * FROM `user`";
$Register = mysql_query($query_Register, $Dbconnection) or die(mysql_error());
$row_Register = mysql_fetch_assoc($Register);
$totalRows_Register = mysql_num_rows($Register);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="CSS/layout.css" rel="stylesheet" type="text/css"/>
<link href="CSS/menu.css" rel="stylesheet" type="text/css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register</title>
<script src="/login&amp;registration system/SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="/login&amp;registration system/SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="/login&amp;registration system/SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<link href="/login&amp;registration system/SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="/login&amp;registration system/SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="/login&amp;registration system/SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
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
      <h1>Sign Up</h1>
  </div>
	<div id="ContentLeft">
	  <h2>Your message here</h2>
	  <h6> Fill in the required fields appropriately</h6>
  </div>
    <div id="ContentRight">
      <form id="RegisterForm" name="RegisterForm" method="POST" action="<?php echo $editFormAction; ?>">
        <table width="400" border="0">
          <tr>
            <td><table border="0">
              <tr>
                 <td><span id="sprytextfield1">
                  <label for="LName">FirstName<br />
                    <br />
                  </label>
                  <input name="FName" type="text" class="StyleTextField" id="LName" />
                </span></td>
                <td><span id="sprytextfield2">
                  <label for="LName">LastName<br />
                    <br />
                  </label>
                  <input name="LName" type="text" class="StyleTextField" id="LName" />
                </span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><span id="sprytextfield3">
              <label for="Email">Email<br />
                <br />
</label>
              <input name="Email" type="text" class="StyleTextField" id="Email" />
            <span class="textfieldRequiredMsg">.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><span id="sprytextfield4">
              <label for="UserName">UserName</label>
              <br />
              <br />
<input name="UserName" type="text" class="StyleTextField" id="UserName" />
<span class="textfieldRequiredMsg">.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><table border="0">
              <tr>
                <td><span id="sprypassword1">
                  <label for="Password">Password</label>
                  <br />
                  <br />
                  <input name="Password" type="password" class="StyleTextField" id="Password" />
                </span></td>
                <td><span id="spryconfirm1">
                  <label for="ConfirmPassword">ConfirmPassword</label>
                  <br />
                  <br />
 <input name="ConfirmPassword" type="Confirmpassword" class="StyleTextField" id="ConfirmPassword" />
                </span></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><input name="Register" type="submit" class="StyleTextField" id="Register" value="Register" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="RegisterForm" />
      </form>
    </div>
</div>
<div id="Footer"></div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "Password");
</script>
</body>
</html>
<?php
mysql_free_result($Register);
?>
