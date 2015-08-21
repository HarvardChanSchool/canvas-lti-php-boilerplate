<?php

// This file contain your app settings. If you are using a source control software your should probably take this file out of versionning. You can add some custom definitions at the end of the file.	

// Your OAUTH CLIENT ID that will be used by the LMS to identify your app
$oauth_clientid = '{{YOUR-APP-OAUTH_CLIENT_ID}}'; 

// Your SECRET that will be used by the LMS to protect sensitive data
$oauth_secret = '{{YOUR-APP-OAUTH_SHARED_SECRET}}'; 

// If you have an app wide token (usually provided by the LMS admin team) put it here, if you want your users to authenticate and use their own token you will need to create a LTI Client method that overide the class property after/constuct.
$oauth_token = '{{YOUR-APP-OAUTH_TOKEN}}';

// This is an internal paramater, it will be used as an HTML class on the <body> of the app as well as anywhere where it might be usefull to identify the app (i.e: Cookies prefix, LocalStorage, etc..)  
$id = '{{YOUR-APP-ID}}';
	
?>