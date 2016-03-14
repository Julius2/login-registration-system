<?php require_once('Connections/Dbconnection.php'); ?>
<?php @session_start(); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "2";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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

$currentPage = $_SERVER["PHP_SELF"];

if ((isset($_POST['DeleteUserhiddenField'])) && ($_POST['DeleteUserhiddenField'] != "")) {
  $deleteSQL = sprintf("DELETE FROM ``user`` WHERE User_id=%s",
                       GetSQLValueString($_POST['DeleteUserhiddenField'], "int"));

  mysql_select_db($database_Dbconnection, $Dbconnection);
  $Result1 = mysql_query($deleteSQL, $Dbconnection) or die(mysql_error());

  $deleteGoTo = "Admin-manageUsers.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_manageusers = 10;
$pageNum_manageusers = 0;
if (isset($_GET['pageNum_manageusers'])) {
  $pageNum_manageusers = $_GET['pageNum_manageusers'];
}
$startRow_manageusers = $pageNum_manageusers * $maxRows_manageusers;

mysql_select_db($database_Dbconnection, $Dbconnection);
$query_manageusers = "SELECT * FROM `user` ORDER BY `Timestamp` DESC";
$query_limit_manageusers = sprintf("%s LIMIT %d, %d", $query_manageusers, $startRow_manageusers, $maxRows_manageusers);
$manageusers = mysql_query($query_limit_manageusers, $Dbconnection) or die(mysql_error());
$row_manageusers = mysql_fetch_assoc($manageusers);

if (isset($_GET['totalRows_manageusers'])) {
  $totalRows_manageusers = $_GET['totalRows_manageusers'];
} else {
  $all_manageusers = mysql_query($query_manageusers);
  $totalRows_manageusers = mysql_num_rows($all_manageusers);
}
$totalPages_manageusers = ceil($totalRows_manageusers/$maxRows_manageusers)-1;

$queryString_manageusers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_manageusers") == false && 
        stristr($param, "totalRows_manageusers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_manageusers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_manageusers = sprintf("&totalRows_manageusers=%d%s", $totalRows_manageusers, $queryString_manageusers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="CSS/layout.css" rel="stylesheet" type="text/css"/>
<link href="CSS/menu.css" rel="stylesheet" type="text/css"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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
      <h1>Admin CP</h1>
</div>
	<div id="ContentLeft">
	  <h2>Admin Links</h2>
	  <h6>Links here</h6>
  </div>
    <div id="ContentRight">
      <table width="670" border="0" align="center">
        <tr>
          <td align="right" valign="top">Showing&nbsp;<?php echo ($startRow_manageusers + 1) ?>to <?php echo min($startRow_manageusers + $maxRows_manageusers, $totalRows_manageusers) ?> of <?php echo $totalRows_manageusers ?></td>
        </tr>
        <tr>
          <td align="center" valign="top"><?php do { ?>
            <?php if ($totalRows_manageusers > 0) { // Show if recordset not empty ?>
              <table width="500" border="0">
                <tr>
                  <td><?php echo $row_manageusers['FirstName']; ?><?php echo $row_manageusers['LastName']; ?><?php echo $row_manageusers['Email']; ?></td>
                </tr>
                <tr>
                  <td><form id="DeleteUserForm" name="DeleteUserForm" method="post" action="">
                    <input name="DeleteUserhiddenField" type="hidden" id="DeleteUserhiddenField" value="<?php echo $row_manageusers['User_id']; ?>" />
                    <input type="submit" name="Deleteuserbutton" id="Deleteuserbutton" value="DeleteUser" />
                  </form></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
              </table>
              <?php } // Show if recordset not empty ?>
            <?php } while ($row_manageusers = mysql_fetch_assoc($manageusers)); ?></td>
        </tr>
        <tr>
          <td align="right" valign="top"><a href="<?php printf("%s?pageNum_manageusers=%d%s", $currentPage, max(0, $pageNum_manageusers - 1), $queryString_manageusers); ?>">
          <?php if ($pageNum_manageusers < $totalPages_manageusers) { // Show if not last page ?>
            Next
  <?php } // Show if not last page ?>
          <?php if ($pageNum_manageusers > 0) { // Show if not first page ?>
            Previous
  <?php } // Show if not first page ?>
          </a></td>
        </tr>
      </table>
    </div>
</div>
<div id="Footer"></div>
</div>
</body>
</html>
<?php
mysql_free_result($manageusers);
?>
