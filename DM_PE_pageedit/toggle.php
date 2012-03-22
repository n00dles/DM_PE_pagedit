<?php 

// Setup inclusions
$load['plugin'] = true;

// Include common.php
include('../../admin/inc/common.php');

// Variable settings
$userid = login_cookie_check();

// Get passed variables
$id 		=  isset($_GET['id']) ? $_GET['id'] : null;
$func    = isset($_GET['func']) ? $_GET['func'] : null;
$value    = isset($_GET['value']) ? $_GET['value'] : null;
$nonce    = isset($_GET['nonce']) ? $_GET['nonce'] : null;
$path 		= GSDATAPAGESPATH;

if(check_nonce($nonce, "private","toggle.php")) {
	$file = GSDATAPAGESPATH.$id.".xml";
	$xml = getXML($file);
	$private= $xml->xpath('/item/private');
	$oldprivate = (string)$private[0];
	if ($oldprivate == null) {
		$private[0][0]="Y";
		echo "P1"; 
	} else {
		$private[0][0]='';
		echo "P0";
	}
	$bakfile = GSBACKUPSPATH."pages/". $id .".bak.xml";
	copy($file, $bakfile);
	XMLsave($xml, $file);
	create_pagesxml(true);
} 

if(check_nonce($nonce, "menu","toggle.php")) {
	$file = GSDATAPAGESPATH.$id.".xml";
	$xml = getXML($file);
	$status= $xml->xpath('/item/menuStatus');
	$oldstatus = (string)$status[0];
	if ($oldstatus == null) {
		$status[0][0]="Y";
		echo "M1"; 
	} else {
		$status[0][0]='';
		echo "M0";
	}
	$bakfile = GSBACKUPSPATH."pages/". $id .".bak.xml";
	copy($file, $bakfile);
	XMLsave($xml, $file);
	create_pagesxml(true);
} 


?>
