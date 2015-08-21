<?php
	
	ini_set('display_errors',1); 
	error_reporting(E_ALL);
	
	include('local-settings.php');
		
	include('inc/toolkit.php');
	
	include('inc/lti-client.php');
	
	$app = new LTI_Client($id,$oauth_clientid,$oauth_secret,$oauth_token);
	
	$app->init();
	
	$app->display_header("Your optional app title goes here");
		
	$app->display_header("Your optional app copy-write/footer goes here");
	
?>