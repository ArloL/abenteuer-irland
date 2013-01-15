<?php

require_once 'site/markdown/markdown.php';

function loadFile($sFilename, $sCharset = 'UTF-8')
{
    $sFilename = strtolower($sFilename);
    if (floatval(phpversion()) >= 4.3) {
        $sData = file_get_contents($sFilename);
    } else {
        if (!file_exists($sFilename)) {
            return -3;
        }
        $rHandle = fopen($sFilename, 'r');
        if (!$rHandle) {
            return -2;
        }

        $sData = '';
        while (!feof($rHandle)) {
            $sData .= fread($rHandle, filesize($sFilename));
        }
        fclose($rHandle);
    }
    if (($sEncoding = mb_detect_encoding($sData, 'auto', true)) != $sCharset) {
        $sData = mb_convert_encoding($sData, $sCharset, $sEncoding);
    }
    return $sData;
}

function getContent($sName, $sLanguage = '')
{
    $sContent = loadFile('content/' . $sLanguage . '/' . $sName . '.txt');
    return Markdown($sContent);
}

function getPageConfig($sName)
{
    $sCompletePath = 'content/' . dirname($sName) . '/content.php';
    if (file_exists($sCompletePath) === true) {
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

function getDirConfig($dir)
{
    if (file_exists('content/' . $dir . '/content.php') === true) {
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

function getMergedDirConfig($sName)
{
    $aDirConfigs = array();
    $aDirs = explode('/', dirname($sName));
    foreach ($aDirs as $sDir) {
        $aConfig = getDirConfig($sDir);
        $aDirConfigs = array_merge($aDirConfigs, $aConfig);
    }
    return $aDirConfigs;
}

function getConfig()
{
    require 'site/defaults.php';
    if (file_exists('site/custom.php')) {
        require 'site/custom.php';
    }

    if (isset($_GET['path']) === true) {
        if (substr($_GET['path'], -1) !== '/') {
            $sName = $_GET['path'];
            $sLowerName = strtolower($sName);
            if (array_key_exists($sLowerName, $aRedirects)) {
                header('HTTP/1.1 301 Moved Permanently');
                header('Location: ' . $aSite['base'] . $aRedirects[$sLowerName]);
                return;
            }
        } else {
            $sName = $_GET['path'] . 'home';
            $sLowerName = strtolower($sName);
        }
    } else {
        $sName = 'home';
        $sLowerName = $sName;
    }

    $aDirs = explode('/', $sLowerName);

    if (in_array($aDirs[0], $aSite['supported-languages']) === false) {
        $sName = $aSite['default-language'] . '/' . $sLowerName;
        $sLowerName = strtolower($sName);
    }

    $aDirConfig = getMergedDirConfig($sLowerName);

    $aPageConfig = getPageConfig($sLowerName);
    if (array_key_exists('content', $aPageConfig) === false) {
        $aPageConfig['content'] = $sLowerName;
    }

    $aConfig = array_merge($aSite, $aDirConfig, $aPageConfig);

    if (file_exists('content/'. $aConfig['content'] . '.txt') === false) {
        $s404 = $aConfig['content-language'].'/404';
        // reset config when file does not exist
        $aPageConfig = getPageConfig($s404);
        $aConfig = array_merge($aSite, $aDirConfig, $aPageConfig);
        $aConfig['content'] = $s404;
        header("HTTP/1.1 404 Not Found");
    } elseif ($sName !== $sLowerName) {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $aSite['base'] . $sLowerName);
        return;
    }

    if ($aConfig['expand-title'] === true) {
        $aConfig['title'] .= ' | ' . $aSite['title'];
    }

    return $aConfig;
}

function hasTranslation($aPage, $sLanguage)
{
    return (array_key_exists($sLanguage, $aPage['translations']) === true && $aPage['translations'][$sLanguage] !== '');
}

function getTranslation($aPage, $sLanguage)
{
    $sTranslation = $aPage['base'];
    if ($sLanguage !== $aPage['default-language']) {
        $sTranslation .= $sLanguage . '/';
    }
    if (hasTranslation($aPage, $sLanguage) === true) {
        $sTranslation .= $aPage['translations'][$sLanguage];
    }
    return $sTranslation;
}

