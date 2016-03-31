<?
header('Content-type: text/html; charset=UTF-8');
	require ("config.php");
	
function getmicrotime() 
{ 
   list($usec, $sec) = explode(" ", microtime()); 
   return ((float)$usec + (float)$sec); 
}

function getNameByDir($dirname)
{
	$categories = file("categories.mdb");
	foreach ($categories as $v)
	{
		list($name,$dir) = explode("|",$v);
		if(trim($dir) == trim($dirname))
			return $name;
	}
	
	return $dirname;
}

function delRowByDir($dirname)
{
	$categories = file("categories.mdb");
	$cont = "";
	foreach ($categories as $v)
	{
		list($name,$dir) = explode("|",$v);
		if(trim($dir) != trim($dirname))
			$cont .= $v;
	}
	$handle = fopen("categories.mdb", "w");
	fwrite($handle,$cont);
}

function renameCategory($dirname,$newname)
{
	$categories = file("categories.mdb");
	$cont = "";
	foreach ($categories as $v)
	{
		list($name,$dir) = explode("|",$v);
		if(trim($dir) != trim($dirname))
			$cont .= $v;
		else
			$cont .= $newname."|".$dir."\n";
	}
	$handle = fopen("categories.mdb", "w");
	fwrite($handle,$cont);
}

function getCategoryList()
{
	$categories = file("categories.mdb");
	$cats = array();
	foreach ($categories as $v)
	{
		list($name,$dir) = explode("|",$v);
		if(!empty($name))
			$cats[] = trim($name);
	}
	
	return $cats;
}

function getDirectoryList()
{
	$categories = file("categories.mdb");
	$dirs = array();
	foreach ($categories as $v)
	{
		list($name,$dir) = explode("|",$v);
		if(!empty($dir))
			$dirs[] = trim($dir);
	}
	
	return $dirs;
}

?>