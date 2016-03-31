<?
header('Content-type: text/html; charset=UTF-8');
	include("inc/header.php");
	define('LF', chr(10));
	error_reporting(0);

	$adm_login=$_POST['adm_login'];
	$adm_pass=$_POST['adm_pass'];

	if ($adm_login&&$adm_pass) {
	  $File=fopen('config.php','w');
	  fwrite($File,'<?'.LF);
	  fwrite($File,'$admin_login="'.$adm_login.'";'.LF);
	  fwrite($File,'$admin_pass="'.$adm_pass.'";'.LF);
	  fwrite($File,'?>'.LF);
	  fclose($File);

	  print '<table><tr><td style="color:#ffffff">';
	  print 'Installed.<br />';
	  print '1. Delete file install.php<br />2. Change attributes to 0755 for config.php.<br /> 3. Change attributes to 0777 for video_gallery folder.<br />';
	  print 'Link to Admin.panel: <a href="admin.php">admin.php</a>';
	  print '</td></tr></table>';
	} else {
	  $arr = file("config.php");
	  foreach($arr as $v){
		$a=explode('"',$v);
		if ($a[0]=='$admin_login=') $s1=$a[1];
		if ($a[0]=='$admin_pass=') $s2=$a[1];
	  }

	  print '<table><tr><td style="color:#FFFFFF ">';
	  print '<b>Please change attributes to 0777 for config.php, video_gallery folders</b><br />';
	  print '<br />';
	  print '<form name="form1" method="post" action="install.php">';
	  print '  Enter admin login: <input type="text" name="adm_login" value="'.$s1.'" / style="border-color:#777777; border-width:1px; border-style:solid; background-color:#000000; color:#777777 "><br><br style="line-height:6px ">';
	  print '  Enter admin password: <input name="adm_pass" type="password" value="'.$s2.'" / style="border-color:#777777; border-width:1px; border-style:solid; background-color:#000000; color:#777777 "><br><br><br style="line-height:6px ">';
	  print '  <br />';
	  print '  <input type="submit" value="" name="Submit" style="width:78px; height:21px; border-width:0px; border-style:solid; background-image:url(pics/submit.jpg)"/>';
	  print '</form>';
	  print '</td></tr></table>';
	};
	print "</body></html>";
?>