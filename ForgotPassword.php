<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="CSS/layout.css" rel="stylesheet" type="text/css"/>
<link href="CSS/menu.css" rel="stylesheet" type="text/css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ForgotPassword</title>
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
      <h1>Email Passowrd</h1>
  </div>
	<div id="ContentLeft">
	  <h2>EMPW Message</h2>
	  <h6>Your message</h6>
  </div>
    <div id="ContentRight">
      <form id="EPWForm" name="EPWForm" method="POST" action="EMPW-Script.php">
        <span id="sprytextfield1">
        <label for="Email"></label>
        <input name="Email" type="text" class="StyleTextField" id="Email" />
        <span class="textfieldRequiredMsg">A value is required.</span></span><br><br>
        <input type="submit" name="EMPWButton" id="EMPWButton" value="Email Password" />
      </form>
  </div>
</div>
<div id="Footer">
  <p>&nbsp;</p>
  <p>Your Name <a href="Admin.php">Admin</a></p>
</div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
</script>
</body>
</html>