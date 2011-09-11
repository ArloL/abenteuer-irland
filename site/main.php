<?php

require_once 'site/markdown/markdown.php';

function loadFile($sFilename, $sCharset = 'UTF-8') {
	$sFilename = strtolower($sFilename);
	if (floatval(phpversion()) >= 4.3) {
		$sData = file_get_contents($sFilename);
	} else {
		if (!file_exists($sFilename)) return -3;
		$rHandle = fopen($sFilename, 'r');
		if (!$rHandle) return -2;

		$sData = '';
		while(!feof($rHandle))
		$sData .= fread($rHandle, filesize($sFilename));
		fclose($rHandle);
	}
	if (($sEncoding = mb_detect_encoding($sData, 'auto', true)) != $sCharset)
	$sData = mb_convert_encoding($sData, $sCharset, $sEncoding);
	return $sData;
}

function getContent($sName, $sLanguage = '') {
	$sContent = loadFile('content/' . $sLanguage . '/' . $sName . '.txt');
	return Markdown($sContent);
}

function getPageConfig($sName) {
	$sCompletePath = 'content/' . dirname($sName) . '/content.php';
	if (file_exists($sCompletePath) === TRUE) {
		require $sCompletePath;
		if (isset($aPages) && isset($aPages[basename($sName)])) {
			return $aPages[basename($sName)];
		} else {
			return array();
		}
	} else {
		return array();
	}
}

function getDirConfig($dir) {
	if (file_exists('content/' . $dir . '/content.php') === TRUE) {
		require 'content/' . $dir . '/content.php';
		if (isset($aDirectory)) {
			return $aDirectory;
		} else {
			return array();
		}
	} else {
		return array();
	}
}

function getMergedDirConfig($sName) {
	$aDirConfigs = array();
	$aDirs = explode('/', dirname($sName));
	foreach($aDirs as $sDir) {
		$aConfig = getDirConfig($sDir);
		$aDirConfigs = array_merge($aDirConfigs, $aConfig);
	}
	return $aDirConfigs;
}

function getConfig() {
	require 'site/defaults.php';
	require 'site/custom.php';

	if (isset($_GET['path']) === TRUE) {
		if (substr($_GET['path'], -1) !== '/') {
			$sName = $_GET['path'];
			if (array_key_exists($sName, $aRedirects)) {
				header('HTTP/1.1 301 Moved Permanently');
				header('Location: ' . $aRedirects[$sName]);
				return;
			}
		} else {
			$sName = $_GET['path'] . 'home';
		}
	} else {
		$sName = 'home';
	}

	if ($sName !== strtolower($sName)) {
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: ' . strtolower($sName));
		return;
	}

	$aDirs = explode('/', $sName);

	if (in_array($aDirs[0], $aSite['supported-languages']) === FALSE) {
		$sName = $aSite['default-language'] . '/' . $sName;
	}

	$aDirConfig = getMergedDirConfig($sName);

	$aPageConfig = getPageConfig($sName);
	if (array_key_exists('content', $aPageConfig) === FALSE) {
		$aPageConfig['content'] = $sName;
	}

	$aConfig = array_merge($aSite, $aDirConfig, $aPageConfig);

	if (file_exists('content/'. $aConfig['content'] . '.txt') === FALSE) {
		$aConfig = array_merge($aSite, $aDirConfig);
		$aConfig['content'] = $aConfig['content-language'].'/404';
		header("HTTP/1.1 404 Not Found");
	}

	if ($aConfig['expand-title'] === TRUE) {
		$aConfig['title'] .= ' | ' . $aSite['title'];
	}

	return $aConfig;
}

function hasTranslation($aPage, $sLanguage) {
	return (array_key_exists($sLanguage, $aPage['translations']) === TRUE && $aPage['translations'][$sLanguage] !== '');
}

function getTranslation($aPage, $sLanguage) {
	$sTranslation = $aPage['base'];
	if ($sLanguage !== $aPage['default-language']) {
		$sTranslation .= $sLanguage . '/';
	}
	if (hasTranslation($aPage, $sLanguage) === TRUE) {
		$sTranslation .= $aPage['translations'][$sLanguage];
	}
	return $sTranslation;
}