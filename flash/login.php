<?
	$login=$_POST['login'];
	$pass=$_POST['pass'];

if (!isset($login)&&!isset($pass)) {
	print'<html><link href="main.css" rel="stylesheet" type="text/css"><body style="background-color: #161616;">';
	print'<table width="100%" height="100%" border="0"><tr><td valign="middle"  style="color:#FFFFFF;font-family: Tahoma; font-size:11px " align="center">';
	print'<img src="pics/text_top.jpg" border="0"><br><br>';
	print'<img src="pics/logo.jpg" border="0">';

	print'<form action="login.php" method="post">';
 print '<strong>Login:</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="login" type="text" maxlength="16"  style="border-color:#777777; border-width:1px; border-style:solid; background-color:#000000; color:#777777 "/><br><br>';
 print '<strong>Password:</strong> &nbsp;&nbsp;<input name="pass" type="password" maxlength="16"  style="border-color:#777777; border-width:1px; border-style:solid; background-color:#000000; color:#777777 "/><br><br>';
	
	
	
	print'<input name="ENTER" type="submit" value="" style="width:78px; height:21px; border-width:0px; border-style:solid; background-image:url(pics/submit.jpg)"/>';
	print'</form>';
	print'</td></tr></table></body</html>';
} else {
	$login = str_replace("'", "", $login);
	$login = substr($login,0,16);
	require ("config.php");

	$a1=strcmp($admin_login,$login);unset($admin_login);
	$a2=strcmp($admin_pass,$pass);unset($admin_pass);
	$COOKIE_LOGIN_NAME='login';
	$COOKIE_PASSW_NAME='pass';

	if (($a1==0)&&($a2==0)) {
	  setcookie($COOKIE_LOGIN_NAME, $login, 0);
	  setcookie($COOKIE_PASSW_NAME, md5($pass), 0);
	  header("Location: admin.php");
	  exit;
	} else {
	  unset($pass);unset($login);
	  setcookie($COOKIE_LOGIN_NAME, '', time()+3600);
	  setcookie($COOKIE_PASSW_NAME, '', time()+3600);
	  header("Location: login.php");
	};
}

print "<noscript>";
?>
