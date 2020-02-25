<?php
  // This script tracks the usage of the rz10.de/n/... URL Shortener for SAP
	// Notes.
	// Warning: gethostbyaddr is quite inefficient and can cause the system to
	// become very slow if it is used a lot. Depending on the traffic it might be
	// neccessary to remove it.
	
	$sapNotePrefix = "https://launchpad.support.sap.com/#/notes/";
	
	$thisFile      = basename(__FILE__);
	$thisPath      = str_replace($thisFile,"",__FILE__);
	
	$logPath       = "$thisPath"."../../log/";
	$logFileName   = "mindnote-log.csv";
	$logFile       = $logPath.$logFileName;
	
	if ($_GET['note']) {
		$noteNumber = preg_replace('/[^0-9]/Usi','',$_GET['note']); 
		$noteLink   = $sapNotePrefix . $noteNumber;

		// Tracking:
		$userTime               = $_SERVER['REQUEST_TIME'];
		$userIp                 = $_SERVER['REMOTE_ADDR'];
		$userIpBehindProxy      = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$userCarrier            = gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$userCarrierBehindProxy = gethostbyaddr($_SERVER['HTTP_X_FORWARDED_FOR']);
		$userReferer            = $_SERVER["HTTP_REFERER"];
		$userNote               = $noteNumber;
		if ($_GET['source']) {
			$userSource = preg_replace('/[,]/','',$_GET['source']); 
		} else {
			$userSource = "'unknown'";
		}

		// Append to tracking file
		$fp = fopen($logFile,'a');
		fputcsv($fp, array($userTime, $userIp, $userCarrier, $userIpBehindProxy, $userCarrierBehindProxy, $userReferer, $userNote, $userSource));
		fclose($fp);

		// Dauerhafte PHP-Weiterleitung (Statuscode 301)
		header("HTTP/1.1 301 Moved Permanently");
		header("Location:$noteLink"); 
	}
	// Zur Sicherheit ein exit-Aufruf, falls Probleme aufgetreten sind
	exit;
?>
