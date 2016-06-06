<?php
final class IdxConfig {
  
  const HOST_NAME = "http://idx-gamechanger.herokuapp.com/";
  const API_VERSION = 1;
  private static $apiKey;
  private static $baseUrl;
  private static $baseDir;
  private static $imageDir;
  
  public static function initialize($apiKey, $imageDir = "", $idxUIParam = "") {
    self::$apiKey = $apiKey;
    $hostname = getenv('HTTP_HOST');
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    self::$baseUrl = $protocol . $hostname;
    self::$imageDir = $imageDir;
    
    global $idxUI;
    global $idxClient;
    
    if ($idxUIParam != "")
      $idxUI = $idxUIParam;
    else
      $idxUI = new IdxUI();
    $idxClient = new IdxClient();
  }
  
  public static function apiKey() {
    return self::$apiKey;
  }

  public static function baseUrl() {
    return self::$baseUrl;
  }
  
  public static function baseDir() {
    return self::$baseDir;
  }
  
  public static function imageDir() {
    return self::$imageDir;
  }
  
}
  
?>