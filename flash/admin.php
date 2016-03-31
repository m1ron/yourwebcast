<?
	error_reporting(0);
	define('LF', chr(10));
	
	include('init.php');

	$COOKIE_LOGIN_NAME='login';
	$COOKIE_PASSW_NAME='pass';
	$login = $_COOKIE[$COOKIE_LOGIN_NAME];
	$pass = $_COOKIE[$COOKIE_PASSW_NAME];
	$a1=strcmp($admin_login,$login);unset($admin_login);
	$a2=strcmp(md5($admin_pass),$pass);unset($admin_pass);
if (($a1!=0)or($a2!=0)) {
	header("Location: login.php");
	exit;
} else {

Header('Cache-Control: no-cache, must-revalidate');
Header('Pragma: no-cache');
Header('Expires: Mon,26 Jul 1980 05:00:00 GMT');

include("inc/header.php");

$xml=new SimpleXmlElement("<?xml version='1.0' standalone='yes'?><movies></movies>");

function loadXML() {
	global $xml;
	if (file_exists('video.xml')) {
	    $xml = simplexml_load_file('video.xml');
//print_r ($xml);
	} else {
	    exit('Failed to open xml.');
	}
	return ;
};
loadXML();

$edit_dir=-1;
if (isset($_GET['edit_dir'])) { $edit_dir=$_GET['edit_dir']; };
if (isset($_POST['edit_dir'])) { $edit_dir=$_POST['edit_dir']; };
if ($edit_dir>-1) {
	print '<div style="margin-left:15px; margin-top:20px; margin-bottom:20px; width:410px; border:solid 1px #545454;"><div style="padding:10px 20px 30px 20px; //padding-top:5px;" ';
	print '<table><tr><td style="color:#ffffff">'."\n";
	print "<form action=\"admin.php?dir_selected=$edit_dir\" method=\"post\">\n";
	print "<span style='font-size:11px; font-weight:bold; color:#fff;'>Category to rename:&nbsp;&nbsp;</span>\n";
	print "<span style='color:#fff;'><b>".$xml->gallery[(int) $edit_dir]->id."</b></span><br />\n";
	print "<div><div style='font-size:11px; float:left; font-weight:bold;  color:#fff;'>New category name:&nbsp;&nbsp;&nbsp;&nbsp;</div>";
	print "<input style='float:left;' name=\"ren_dir\" type=\"text\" />";
	print "<input value='' style='float:left; width:51px; height:21px; border-width:0px; margin-left:20px; border-style:solid; background-image:url(pics/add.jpg);' type=\"submit\" /></form>\n";
	print '</div></div></div>';
	print "</td></tr></table>\n";
	print "</body></html>";
} else {

include("inc/vtop.php");

$dir_selected=0;
if (isset($_GET['dir_selected'])) {$dir_selected=$_GET['dir_selected'];};

function saveXML() {
	global $xml;
	$handle = fopen("video.xml", "w");
	$str="<?xml version='1.0' standalone='yes'?>".LF;
	$str.="<movies>".LF;
	foreach($xml->gallery as $gallery) {
	   $str.=" <gallery>".LF;
	   $str.=" <id>$gallery->id</id>".LF;
	   foreach($gallery->movie as $movie) {
		$str.=" <movie>".LF;
		$str.="  <title>$movie->title</title>".LF;
		$str.="  <file>$movie->file</file>".LF;
		$str.="  <thumb>$movie->thumb</thumb>".LF;
		$str.="  <duration>$movie->duration</duration>".LF;
		$str.=" </movie>"."\n";
	   };
	   $str.=" </gallery>".LF;
	};
	$str.="</movies>".LF;
	fwrite($handle,$str);
	return ;
};
function indexXML($fname) {
	global $xml;
	global $dir_selected;
	$i=-1;$k=0;
	if (count($xml->gallery[(int) $dir_selected]->movie)>0)
	foreach($xml->gallery[(int) $dir_selected]->movie as $movie) {
		if ($movie->file==$fname) $i=$k;
		$k++;
	}		
	return $i;
};

// delete
	$id=-1;
	if (isset($_GET["delete"])) { $id=indexXML($_GET["delete"]); };
	if ($id>-1) {
		$tt=$xml->gallery[(int) $dir_selected]->movie[(int) $id];
		unlink("video_gallery/".$tt->file);
		if ($tt->thumb!='video.jpg') { unlink("video_gallery/".$tt->thumb); };
		if (count($xml->gallery[(int) $dir_selected]->movie)>1) {
			unset($xml->gallery[(int) $dir_selected]->movie[(int) $id]);
		} else {
			$tt=$xml->gallery[(int) $dir_selected]->movie[(int) $id];
			$tt->file='';
			$tt->title='';
			$tt->thumb='';
			$tt->duration='';
		};
		saveXML();
//		echo '<meta http-equiv="Refresh" content="0; url=admin.php?dir_selected='.$dir_selected.'">';
	};

// sort files
if(isset($_POST['sort'])){
	global $xml;
	$handle = fopen("video.xml", "w");
	$str="<?xml version='1.0' standalone='yes'?>".LF;
	$str.="<movies>".LF;

	$di=0;
	foreach($xml->gallery as $gallery) {
	   $str.=" <gallery>".LF;
	   $str.=" <id>$gallery->id</id>".LF;
	   if ($di==$dir_selected) {
		foreach($_POST['sort'] as $v ){
			$movie=$xml->gallery[(int) $dir_selected]->movie[(int) $v]; 
			$str.="  <movie>".LF;
			$str.="   <title>$movie->title</title>".LF;
			$str.="   <file>$movie->file</file>".LF;
			$str.="   <thumb>$movie->thumb</thumb>".LF;
			$str.="   <duration>$movie->duration</duration>".LF;
			$str.="  </movie>"."\n";
		};
	   } else {
		foreach($gallery->movie as $movie) {
			$str.=" <movie>".LF;
			$str.="  <title>$movie->title</title>".LF;
			$str.="  <file>$movie->file</file>".LF;
			$str.="  <thumb>$movie->thumb</thumb>".LF;
			$str.="  <duration>$movie->duration</duration>".LF;
			$str.=" </movie>"."\n";
	   	};
	   };
	   $str.=" </gallery>".LF;
	   $di++;
	};
	$str.="</movies>".LF;
	fwrite($handle,$str);
	loadXML();
}

// rename category
	if (!empty($_POST['ren_dir'])) {
		$xml->gallery[(int) $dir_selected]->id=$_POST['ren_dir'];
		saveXML();
	};
// add new category
	if (!empty($_POST['new_dir']))
	{
		$tt=$xml->addChild('gallery');
		$tt->addChild('id', $_POST['new_dir']);
		$tt->addChild('movie');
		saveXML();
	}
// delete category
	$del_dir=-1;
	if (isset($_GET['del_dir'])) { $del_dir=$_GET['del_dir']; };
	if ($del_dir>-1) {
	   foreach($xml->gallery[(int) $del_dir]->movie as $movie) {
		if (file_exists("video_gallery/".$movie->file)) {
			unlink("video_gallery/".$movie->file);
		};
		if (($movie->thumb!='video.jpg')&&file_exists("video_gallery/".$movie->thumb)) unlink("video_gallery/".$movie->thumb);
	   };
	   unset($xml->gallery[(int) $del_dir]);
	   saveXML();
	   echo '<meta http-equiv="Refresh" content="0; url=admin.php?dir_selected='.$dir_selected.'">';
	};
// sort categories
if(isset($_POST['cats'])){
	$tmp=$xml->gallery[(int) 0];
	$str = "";
	$txml=simplexml_load_string("<?xml version='1.0' standalone='yes'?><movies></movies>");
	foreach($_POST['cats'] as $v ){
		$gallery=$txml->addChild('gallery');
		$gallery->addChild('id', $xml->gallery[(int) $v]->id);
		foreach($xml->gallery[(int) $v] as $gal1) { 
			$movie=$gallery->addChild('movie');
			$movie->addChild('title', $gal1->title);
			$movie->addChild('file', $gal1->file);
			$movie->addChild('thumb', $gal1->thumb);
			$movie->addChild('duration', $gal1->duration);
		};
	}
	$xml=$txml;
	saveXML();
}
/////////////
// left menu
	echo '<script src="inc/ui.base.js"></script><script src="inc/ui.sortable.js"></script>';
	print "</center>
	<div style='background-image:url(pics/table1_top.jpg); height:60px;'>&nbsp;</div></center>
	<div style='background-color:#292929; border:solid 1px #292929;'>
	";

	print '<form method="post" id="f1"><ul id="myList" style="margin-left:0;">';
	$i=0;
	foreach($xml->gallery as $gal) {
		print '<li style="color:#ffffff">';
		print "<input type='hidden' name='cats[]' value='".$i."'>";
		print "<a href=\"admin.php?del_dir=$i\" class=\"menu_2\"><img src=\"pics/delete.gif\" border=0 alt=\"delete\"></a> ";
		print "<a href=\"admin.php?edit_dir=$i\" class=\"menu_2\"><img src=\"pics/edit.gif\" border=0 alt=\"edit\"></a> ";
		if ($i==$dir_selected) {
			print "<a href=\"admin.php?dir_selected=$i\" class=\"menu_1\">".$gal->id."</a>";
		} else {
			print "<a href=\"admin.php?dir_selected=$i\" class=\"menu_2\">".$gal->id."</a>";
		};
		$i++;
	};
	print '</ul>';

	if (count($xml->gallery[(int) $dir_selected]->movie)>0) {
?> 	<img src="pics/sort.jpg" title="Sort Galleries" style="cursor:pointer; margin-left:10px;" onclick="if(this.src.indexOf('sort') != -1){$('#myList').sortable({});this.src = 'pics/save.jpg'}else{$('#myList').sortable('disable');document.getElementById('f1').submit()}" />
	</form><? };
	print '<br><br><table width="100%"><tr><td  style="color:#FFFFFF "><strong class="white"  style=" font-size:11px ">&nbsp;&nbsp;Add new category:</strong><br /><br /><form action="admin.php" method="post"><input name="dir_selected" type="hidden" value="'.$dir_selected.'"><input type="text" name="new_dir" style="border:1px solid #777777; border-style:solid; background-color:#000000; color:#777777 "><br><input name="OK" type="submit" value=""  style="width:51px; height:21px; border-width:0px; margin-left:5px; padding-top:5 px; margin-top:10px; border-style:solid; background-image:url(pics/add.jpg)"></form></td></tr></table>';
	print "</div></td><td>";


// add new
if (isset($_FILES["flv"])) {
	if (is_uploaded_file($_FILES['flv']['tmp_name'])) {
		$filename = $_FILES['flv']['tmp_name'];
		$upload_name = $_FILES['flv']['name'];
		copy($filename, "video_gallery/".$upload_name);
		unlink($filename);

		$handle = fopen("video.xml", "w");
		$str="<?xml version='1.0' standalone='yes'?>".LF;
		$str.="<movies>".LF;
		$i=0;
		foreach($xml->gallery as $gallery) {
			$str.=" <gallery>".LF;
			$str.=" <id>$gallery->id</id>".LF;
			if ($dir_selected==$i) {
				$str.=" <movie>".LF;
				$str.="  <title>".urlencode(trim(stripslashes($_POST['name'])))."</title>".LF;
				$str.="  <file>".$_FILES['flv']['name']."</file>".LF;
				if (is_uploaded_file($_FILES['screenshot']['tmp_name'])) {
					$filename = $_FILES['screenshot']['tmp_name'];
					$upload_name = $_FILES['screenshot']['name'];
					copy($filename, "video_gallery/".$upload_name);
					unlink($filename);
					$str.="  <thumb>".$_FILES['screenshot']['name']."</thumb>".LF;
				} else {
					$str.="  <thumb>video.jpg</thumb>".LF;
				};
				$str.="  <duration>".$_POST['duration']."</duration>".LF;
				$str.=" </movie>"."\n";
			};
			foreach($gallery->movie as $movie) {
				if (($movie->file!='')||($dir_selected!=$i)) {
				  $str.=" <movie>".LF;
				  $str.="  <title>$movie->title</title>".LF;
				  $str.="  <file>$movie->file</file>".LF;
				  $str.="  <thumb>$movie->thumb</thumb>".LF;
				  $str.="  <duration>$movie->duration</duration>".LF;
				  $str.=" </movie>"."\n";
				};
			};
			$str.=" </gallery>".LF;
			$i++;
		};

		$str.="</movies>".LF;
		fwrite($handle,$str);

		loadXML();
	};
};

if (isset($_POST['edit'])) {
	$id=-1;
	$id=(int) indexXML($_POST['edit']);
	if ($id>-1) {
		$xml->gallery[(int) $dir_selected]->movie[$id]->title=urlencode(trim(stripslashes($_POST['name'])));
		$xml->gallery[(int) $dir_selected]->movie[$id]->duration=$_POST['duration'];
		saveXML();
	};
};
if (isset($_GET['edit'])) {
	$id=(int) indexXML($_GET['edit']);
?>
	<div style="margin-left:15px; margin-top:20px; margin-bottom:20px; width:450px; border:solid 1px #545454;">
  <form action="admin.php?dir_selected=<? echo $dir_selected ?>" enctype="multipart/form-data" method="post">
    <input name="edit" type="hidden" value="<?php print $_GET['edit']; ?>" />
    <br />
    <table width="100%"  style="margin-left:15px; margin-top:0px;">
    <tr>
      <td colspan="3"><div  style="padding-bottom:10px;">
          <div class="white"  style=" font-size:11px; font-weight:bold; padding-top:4px; float:left; width:100px; ">Name:</div>
          <input name="name" type="text" value="<?php echo stripslashes(htmlspecialchars(urldecode($xml->gallery[(int) $dir_selected]->movie[$id]->title))) ?>" />
        </div>
        <div>
          <div class="white"  style="font-size:11px; font-weight:bold; padding-top:4px; float:left; width:100px; ">Duration:</div>
          <input name="duration" style="float:left; type="text" value="<?php echo $xml->gallery[(int) $dir_selected]->movie[$id]->duration ?>" />
          <input name="OK" type="submit" value=""  style="width:51px; height:21px; border-width:0px; margin-left:20px; padding-top:3px; border-style:solid; background-image:url(pics/add.jpg)">
 </div>
  </form>
</div></td></tr></table>
<?
} else {
?>
	<script language="JavaScript">
	function my_click() {
		element=document.getElementById("preloader");element.style.display="";
		element2=document.getElementById("itms");element2.style.display="none";
	}
	</script>
<div id="preloader" style="display:none; ">
  <center>
    <br />
    <br />
    <img src="pics/loading.gif" /><br />
    <div class="loading_txt">Loading. Please wait. </div>
  </center>
</div>

<? 		$tt=$xml->gallery[(int) $dir_selected];
		if (count($tt)>0) {?>
<div style="margin-left:15px; margin-top:20px; margin-bottom:20px; width:595px; border:solid 1px #545454;">
  <div style="padding:0 0 0 20px;">
    <div class="white"  style="font-size:11px; position:absolute; margin-top:-27px; //margin-top:-8px; margin-left:10px; color:#727272; background:#161616; padding:0 4px;">Add
      new video</div>
    <div style="font-size:11px; color:#fff; margin-top:20px;"><span style="color:#fc590a;font-weight:bold;">Note!</span>&nbsp;&nbsp;Upload
      only FLV files. You can convert you video file to FLV online here:
      <a href="http://www.demo-templates.com" style="color:#fc590a;">www.demo-templates.com</a>
    </div>
    <form action="admin.php?dir_selected=<? echo $dir_selected ?>" enctype="multipart/form-data" method="post">
      <input name="ren_dir" type="hidden" value="" />
      <div style="padding-bottom:10px; padding-top:10px;">
        <div class="white"  style=" font-size:11px; font-weight:bold; padding-top:4px; float:left; width:100px; ">Name:</div>
        <input name="name" type="text" value="" style="width:355px;" />
      </div>
      <div style="padding-bottom:10px;">
        <div class="white"  style="font-size:11px; font-weight:bold; padding-top:4px; float:left; width:100px; ">Duration:</div>
        <input name="duration" type="text" value="" style="width:146px;" />
      </div>
      <div  style="margin-bottom:10px;">
        <div class="white" style="font-size:11px; font-weight:bold; padding-top:4px; float:left; width:100px; ">FLV
          file:</div>
        <input type="file" name="flv" style="border:1px solid #777777; border-style:solid; background-color:#fff; color:#666666; width:355px;">
      </div>
      <div>
        <div class="white"  style=" font-size:11px; font-weight:bold; padding-top:4px; float:left; width:100px; ">Screenshot:</div>
        <input type="file" name="screenshot" style="border:1px solid #777777; border-style:solid; background-color:#fff; color:#666666; width:355px;">
        <input name="OK" type="submit" value=""  style="width:51px; height:21px; border-width:0px; margin-left:20px; border-style:solid; background-image:url(pics/add.jpg)" onclick="my_click();">
      </div>
    </form>
  </div>
</div>
<? }; ?>
		<form method="POST" id="f2">
<?		if ((count($tt->movie)>0)&&($tt->movie[0]->file!='')) {?>
		<img style="cursor:pointer;  margin-left:30px;" title="Sort Images" src="pics/sort.jpg" onclick="if(this.src.indexOf('sort') != -1){$('#myList1').sortable({});this.src = 'pics/save.jpg'}else{$('#myList1').sortable('disable');document.getElementById('f2').submit()}" /><br />
<? }; ?>
		<ul qwidth="100%" id="myList1"  qbgcolor="#161616" qstyle="margin:0px; height:50px;">
<?
		$i=0;
		if ((count($tt->movie)>0)&&($tt->movie[0]->file!='')) {
		foreach($tt->movie as $movie) {
			?>
		<li style="clear:both; height:50px; padding:0px; background-color:#313131" bgcolor="#2d2d2d">
			<div style="float:left; height:50px; width:50px; border-right:#161616 5px solid; text-align:center;" >
				<div style="height:17px;"></div>
				<a href="admin.php?delete=<?php echo $movie->file; ?>&dir_selected=<? echo $dir_selected ?>">
				<img src="pics/delete.gif" border=0 alt="delete">
				</a>
			</div>
			<div style="float:left; height:50px; width:50px; border-right:#161616 5px solid; text-align:center;" >
				<div style="height:17px;"></div>
				<a href="admin.php?edit=<?php echo $movie->file; ?>&dir_selected=<? echo $dir_selected ?>">
				<img src="pics/edit.gif" border=0 alt="edit">
				</a>
			</div>
			<div style="float:left; height:50px; width:50px; border-right:#161616 5px solid;">
				<a href="<?php echo 'video_gallery/'.$movie->thumb ?>">
					<img src="<?php echo 'video_gallery/'.$movie->thumb ?>" border="0" width="50" height="50">
				</a>
			</div>
			<div style="float:left; height:50px; width:200px; padding-left:28px; color:#FFFFFF; border-right:#161616 5px solid;">
				<div style="height:17px;"></div>
				<?php echo $movie->file ?>
				<input type='hidden' name='sort[]' value='<?php echo indexXML($movie->file) ?>'>
			</div>
			<div style="float:left; height:50px; width:350px; padding-left:28px; color:#FFFFFF; border-right:#161616 5px solid;">
				<div style="height:17px;"></div>
				<?php echo stripslashes(htmlspecialchars(urldecode($movie->title))) ?>
			</div>
			<div style="float:left; height:50px; width:50px; padding-left:28px; color:#FFFFFF;">
				<div style="height:17px;"></div>
				<?php echo $movie->duration ?>
			</div>
		</li>
<?
			$i++;
		  };
		};
?>
		</ul>
		
	</form>
<?
};
};
	include("inc/bottom.php");
};

?>
