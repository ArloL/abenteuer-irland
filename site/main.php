<?php

require_once 'site/markdown/markdown.php';

function loadFile($sFilename, $sCharset = 'UTF-8') {
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

function getContent($name, $language = '') {
  $txt = loadFile('content/' . $language . '/' . $name . '.txt');
  return Markdown($txt);
}

function getPageConfig($name) {
  $completePath = 'content/' . dirname($name) . '/content.php';
  if (file_exists($completePath) === TRUE) {
    require $completePath;
    if (isset($pages) && isset($pages[basename($name)])) {
      return $pages[basename($name)];
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
    if (isset($directory)) {
      return $directory;
    } else {
      return array();
    }
  } else {
    return array();
  }
}

function mergeDirConfig($name) {
  $dirConfigs = array();
  $dirs = explode('/', dirname($name));
  foreach($dirs as $dir) {
    $conf = getDirConfig($dir);
    $dirConfigs = array_merge($dirConfigs, $conf);
  }
  return $dirConfigs;
}

function getConfig() {
  require 'site/defaults.php';
  require 'site/custom.php';
  
  if (isset($_GET['path']) === TRUE) {
    if (substr($_GET['path'], -1) !== '/') {      
      $name = $_GET['path'];
      if (array_key_exists($name, $redirects)) {
        header('HTTP/1.1 301 Moved Permanently'); 
        header('Location: ' . $redirects[$name]);
        return; 
      }
    } else {      
      $name = $_GET['path'] . 'home';
    }
  } else {
    $name = 'home';
  }
  
  if ($name !== strtolower($name)) {
    header('HTTP/1.1 301 Moved Permanently'); 
    header('Location: ' . strtolower($name));
    return;
  }
  
  $dirs = explode('/', $name);
  
  if (in_array($dirs[0], $site['supported-languages']) === FALSE) {
    $name = $site['default-language'] . '/' . $name;
  }
  
  $dirConfig = mergeDirConfig($name);

  $pageConfig = getPageConfig($name);
  if (array_key_exists('content', $pageConfig) === FALSE) {
    $pageConfig['content'] = $name;
  }
    
  $mergedArray = array_merge($site, $dirConfig, $pageConfig);
  
  if (file_exists('content/'. $mergedArray['content'] . '.txt') === FALSE) {
    $mergedArray = array_merge($site, $dirConfig);
    $mergedArray['content'] = $mergedArray['content-language'].'/404';
    header("HTTP/1.1 404 Not Found");
  }

  if ($mergedArray['expand-title'] === TRUE) {
    $mergedArray['title'] .= ' | ' . $site['title'];
  }
  
  return $mergedArray;
}

function hasTranslation($page, $lang) {
  return (array_key_exists($lang, $page['translations']) === TRUE && $page['translations'][$lang] !== '');
}

function getTranslation($page, $lang) {
  $result = $page['base']; 
  if ($lang !== $page['default-language']) {
    $result .= $lang . '/';
  }
  if (hasTranslation($page, $lang) === TRUE) {
    $result .= $page['translations'][$lang];
  }
  return $result;
}