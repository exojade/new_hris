<?php

    // error_reporting(0);
    // ini_set('display_errors', 0);
    ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED); 
    ini_set('memory_limit', '1024M');
    set_time_limit(500);

    require("constants.php");
    require("functions.php");
    
    require ('./vendor/autoload.php');
    // dump($_SESSION);
	
	date_default_timezone_set('Asia/Manila');
    session_start();

    if(isset($_SESSION["hris"])){

        $site_options = query("select * from site_options");
        $site_options = $site_options[0];

        define("clientID", $site_options["google_clientID"]);
        define("clientSecret", $site_options["google_clientSecret"]);


        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';

        // Get the host (domain or IP address)
        $host = $_SERVER['HTTP_HOST'];

        // Get the port (if it's not the default port 80 for http or 443 for https)
        $port = $_SERVER['SERVER_PORT'];
        $port_suffix = ($port != 80 && $port != 443) ? ":" . $port : "";

        // Get the requested URI
        $uri = $_SERVER['REQUEST_URI'];

        // Combine the parts to form the complete URL
        $urlWithPort = $protocol . "://" . $host;

        // Output the result
        // dump($urlWithPort);


        define("redirectUri", $urlWithPort . "/hris/google_login");


        // $clientID = '538691118774-50b5ak993tc510dlrmoishso1pi8qv2q.apps.googleusercontent.com';
        // $clientSecret = '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';
        // $redirectUri = 'http://hrmis-systems.com:7000/hrmis_system/google_login';
        $scopes = array('https://www.googleapis.com/auth/drive','https://www.googleapis.com/auth/spreadsheets',
        'https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile'
    );
        
    $google = new Google_Client();
        $google->setClientId(clientID);
        $google->setClientSecret(clientSecret);
        $google->setRedirectUri(redirectUri);
       
        $google->setScopes($scopes);
        $google->setAccessType('offline');
    }

   
?>