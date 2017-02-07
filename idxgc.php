<?php
/**
 * Plugin Name: IDX GameChanger
 * Plugin URI: http://www.sdbxstudio.com
 * Description: IDXGC Wordpress Integration
 * Version: 1.0.7
 * Author: Craig Tran
 * Author URI: http://www.sdbxstudio.com
 * Text Domain: sdbxtextdomain
 * License: GPL2
 * GitHub Plugin URI: https://github.com/cntran/idxgc
 * GitHub Branch: master
 */
 
class idxgc {

  function idxgc() {
    $this->__construct();
  }
  
  function __construct() {
  
    // Define constants.
    add_action( 'after_setup_theme', array( &$this, 'constants' ), 1 );
    
    // Load core functions & classes. 
    add_action( 'after_setup_theme', array( &$this, 'functions' ), 2 );
            
    // Run setup finalization
    add_action( 'after_setup_theme', array( &$this, 'finish' ), 3 );
        
  }
  
  function constants() {
    define( 'IDXGC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );    
    define( 'IDXGC_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
    define( 'IDXGC_FUNCTIONS', trailingslashit( IDXGC_PLUGIN_DIR ) . 'idxgc' );
  }
  
  function functions() {
    // Load functions
    $files = scandir( trailingslashit( IDXGC_FUNCTIONS ) );
    foreach ( $files as $file ) {
      $file_ext = explode(".", $file) ; 
      if ($file_ext[count($file_ext) - 1] == 'php') 
        require_once( trailingslashit( IDXGC_FUNCTIONS ) . $file );  
    }  
  }
  
  /*
   * Run upon completion of theme setup
   */
  function finish() {
  }
    
}

$idxgc = new idxgc();
require_once('idxgc-wordpress.php');