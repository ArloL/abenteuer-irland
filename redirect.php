---
layout: nil
---
<?php

$aRedirects = array(
    'home' => '{{site.url}}',
    'en/home' => '{{site.url}}/en/',
    'english' => 'http://business-english-bocholt.de',
    'englisch' => 'http://business-english-bocholt.de',
    'business-english' => 'http://business-english-bocholt.de',
);

$sPath = $_GET['path'];

if (array_key_exists($sPath, $aRedirects)) {
	$sRedirect = $aRedirects[$sPath];
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . $sRedirect);
} else {
	header("HTTP/1.1 404 Not Found");
	include '404.html';
}
