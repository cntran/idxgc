<?php

if ( function_exists( 'idxgcSocialWall' ) )
  add_shortcode( 'idxgcSocialWall', 'idxgcSocialWall' );
if ( function_exists( 'idxgcClientPortal' ) )
  add_shortcode( 'idxgcClientPortal', 'idxgcClientPortal' );
if ( function_exists( 'idxgcListing' ) )
  add_shortcode( 'idxgcListing', 'idxgcListing' );
if ( function_exists( 'idxgcSearchResults' ) )
  add_shortcode( 'idxgcSearchResults', 'idxgcSearchResults' );
if ( function_exists( 'idxgcContactForm' ) )
  add_shortcode( 'idxgcContactForm', 'idxgcContactForm' );
if ( function_exists( 'idxgcCommunity' ) )
  add_shortcode( 'idxgcCommunity', 'idxgcCommunity' );

add_action( 'wp_enqueue_scripts', 'idxgcScripts' );

function idxgcScripts() {
  
    $idxgcFoundation = get_option('idxgcFoundation');
    $idxgcFontAwesome = get_option('idxgcFontAwesome');
    $idxgcFresco = get_option('idxgcFresco');
    $idxgcAddThis = get_option('idxgcAddThis');
    $idxgcGoogleMaps = get_option('idxgcGoogleMaps');
  
    if ($idxgcFoundation == "1") {
      wp_register_style( 'foundation', "//cdnjs.cloudflare.com/ajax/libs/foundation/6.1.2/foundation.css" );
      wp_enqueue_script( 'foundation', "//cdnjs.cloudflare.com/ajax/libs/foundation/6.1.2/foundation.min.js", array('jquery'));
      wp_register_style( 'idxgc', plugin_dir_url("/") . 'idxgc/idxgc/css/idxgc.css');
      wp_register_style( 'wpThemeStyle', get_stylesheet_uri(), array( 'idxgc', 'foundation' ));
      wp_enqueue_style( 'wpThemeStyle' );
    }
    else {
      wp_register_style( 'idxgc', plugin_dir_url("/") . 'idxgc/idxgc/css/idxgc.css');
      wp_register_style( 'wpThemeStyle', get_stylesheet_uri(), array( 'idxgc' ));
      wp_enqueue_style( 'wpThemeStyle' );
    }
    
    if ($idxgcFontAwesome == "1") {
      wp_enqueue_style( 'fontawesome', "//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" );
    }
    
    if ($idxgcFresco == "1") {
      wp_enqueue_style( 'frescoJS', plugin_dir_url("/") . "idxgc/idxgc/js/fresco/css/fresco/fresco.css" );
      wp_enqueue_script( 'frescoJS', plugin_dir_url("/") . "idxgc/idxgc/js/fresco/js/fresco/fresco.js", array('jquery'));
    }
    if ($idxgcGoogleMaps == "1") {
      wp_enqueue_script( 'googleMaps', "https://maps.googleapis.com/maps/api/js?key=AIzaSyDjUOh3pRnrl29ScTmLzskCTnzbSythMPM");
    }
    if ($idxgcAddThis == "1") {
      wp_enqueue_script( 'addThis', "//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4dd2b887572ac73b" );
    }
    
    wp_enqueue_script( 'idxgc', plugin_dir_url("/") . 'idxgc/idxgc/js/idxgc.js', array('jquery') );
    wp_enqueue_script( 'idxgcSumoSelect', plugin_dir_url("/") . 'idxgc/idxgc/js/sumoselect.js', array('jquery'));
}

/* IDXGC DASHBOARD */
function idxgcDashboardSettings() {

  if ( $_REQUEST['saved'] == "save" ) {
            
    $idxgcFoundation = false;
  	if ($_REQUEST['idxgcFoundation'] == "on")
  		$idxgcFoundation = true;
    $idxgcFontAwesome = false;
  	if ($_REQUEST['idxgcFontAwesome'] == "on")
  		$idxgcFontAwesome = true;
    $idxgcFresco = false;
  	if ($_REQUEST['idxgcFresco'] == "on")
  		$idxgcFresco = true;
    $idxgcAddThis = false;
  	if ($_REQUEST['idxgcAddThis'] == "on")
  		$idxgcAddThis = true;
    $idxgcGoogleMaps = false;
  	if ($_REQUEST['idxgcGoogleMaps'] == "on")
  		$idxgcGoogleMaps = true;		
		 
    update_option( 'idxgcFoundation', $idxgcFoundation );
    update_option( 'idxgcFontAwesome', $idxgcFontAwesome );
    update_option( 'idxgcFresco', $idxgcFresco );
    update_option( 'idxgcAddThis', $idxgcAddThis );
    update_option( 'idxgcGoogleMaps', $idxgcGoogleMaps );
   
    header("Location: index.php?saved=true");  
    //die;
  }
  
  $idxgcFoundation = get_option('idxgcFoundation');
  $idxgcFontAwesome = get_option('idxgcFontAwesome');
  $idxgcFresco = get_option('idxgcFresco');
  $idxgcAddThis = get_option('idxgcAddThis');
  $idxgcGoogleMaps = get_option('idxgcGoogleMaps');
  
  
  ?>
  <form method="post">
    <strong>Include libraries:</strong>
    <p>
      <label for="idxgcFoundation">Foundation:</label>&nbsp;
      <input type="checkbox" id="idxgcFoundation" name="idxgcFoundation" <?php if ($idxgcFoundation == "1") : ?>checked="checked"<<?php endif; ?> />
    </p>
    <p>
      <label for="idxgcFontAwesome">Fontawesome:</label>&nbsp;
      <input type="checkbox" id="idxgcFontAwesome" name="idxgcFontAwesome" <?php if ($idxgcFontAwesome == "1") : ?>checked="checked"<<?php endif; ?> />
    </p>
    <p>
      <label for="idxgcFresco">Fresco JS Slideshow:</label>&nbsp;
      <input type="checkbox" id="idxgcFresco" name="idxgcFresco" <?php if ($idxgcFresco == "1") : ?>checked="checked"<<?php endif; ?>/>
    </p>
    <p>
      <label for="idxgcAddThis">Add This:</label>&nbsp;
      <input type="checkbox" id="idxgcAddThis" name="idxgcAddThis" <?php if ($idxgcAddThis == "1") : ?>checked="checked"<<?php endif; ?>/>
    </p>
    <p>
      <label for="idxgcGoogleMaps">Google Maps:</label>&nbsp;
      <input type="checkbox" id="idxgcGoogleMaps" name="idxgcGoogleMaps" <?php if ($idxgcGoogleMaps == "1") : ?>checked="checked"<<?php endif; ?>/>
    </p>
    <p>
    <input id="saved" name="saved" type="hidden" value="save" />
    <input type="submit" value="Save Changes" class="button-primary" /> 
    </p>    
    
  </form>
  <?php
  
}

// Function used in the action hook
function idxgcAddDashboardWidgets() {
  wp_add_dashboard_widget('dashboard_widget', 'IDXGC Settings', 'idxgcDashboardSettings');
}

// Register the new dashboard widget with the 'wp_dashboard_setup' action
add_action('wp_dashboard_setup', 'idxgcAddDashboardWidgets' );


/* REWRITE RULE FOR PROPERTY DETAIL PAGES */
add_action( 'init', 'idxgc_rewrites_init' );
function idxgc_rewrites_init(){
   add_rewrite_rule(
        'mls/(.*)/?$',
        'index.php?pagename=mls&pid=$matches[1]',
        'top' );
}

function idxgcClientPortal() {
  global $idxUI; 
  echo $idxUI->clientPortal();  
}

function idxgcSocialWall() {
  global $idxUI; 
  echo $idxUI->socialWall();  
}

function idxgcListing() {
  
  global $idxClient, $idxUI;
  $listingExists = true;
  $mlsNumber = $idxClient->getMlsNumberFromCurrentUrl();
  try {
    $listing = $idxClient->getListing($mlsNumber);
    $listingId = $idxClient->getListingId($listing);
  }
  catch(Exception $e) {
    echo "<h2>Listing not found.</h2>";
    $listingExists = false;
  }
?> 
<?php if ($listingExists) : ?> 
<div id="idxgc-listing-<?php echo $listing->mls_number; ?>" class="idxgc-listing">
  <?php
    echo $idxUI->listing($listing); 
  ?>
  <?php if ($listing->picture_count > 0) : ?>
    <?php $listingPhotos = $idxUI->listingPhotos($listing); ?>
    <div id="idxgc-listing-photos">
      <h2>Additional Photos</h2>
      <div class="grid-g">
      <?php for($i = 0; $i < count($listingPhotos); $i++) : ?>
        <div class="idxgc-thumbnail grid-u-1 grid-u-md-1-4">
          <a class="fresco" data-fresco-group="<?php echo $listing->mls_number; ?>" href="<?php echo $listingPhotos[$i] . "_640w.jpg"; ?>"><img src="<?php echo $listingPhotos[$i] . "_320w.jpg"; ?>" /></a>
        </div>
      <?php endfor; ?>
      </div>
    </div>
  <?php endif; ?>
  
  <div id="idxgc-listing-map">
  <h2>Property Location</h2>
  <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyDjUOh3pRnrl29ScTmLzskCTnzbSythMPM&q=<?php echo $listing->latitude; ?>,<?php echo $listing->longitude; ?>&zoom=15" style="width:100%; height: auto" frameBorder="0"></iframe>
  </div>
  
  <div id="idxgc-listing-contact" class="grid-g">
    <div class="grid-u-1 grid-box-left-edge grid-u-md-1-2">
    <button class="idxgc-button" data-open="idxgcListingInquiry">Request More Information</button>
    </div>
    <div class="grid-u-1 grid-box-right-edge grid-u-md-1-2">
    <button class="idxgc-button" data-open="idxgcSeeItNow">See It Now</button>
    </div>
  </div>
  
  <div id="idxgc-reciprocity">
    <small>
      <strong>Listing courtesy of:</strong><br />
      <?php echo $listing->list_office_name; ?><br />
      <p>&copy; <?php echo date("Y"); ?> 
      <?php if ($listing->source == "tsmls") : ?>
      Tahoe Sierra Multiple Listing Service. 
      <?php elseif ($listing->source == "nnrmls") : ?>
      Northern Nevada Regional Listing Service.
      <?php elseif ($listing->source == "ivmls") : ?>
      Incline Village Multiple Listing Service.
      <?php elseif ($listing->source == "sltmls") : ?>
      South Tahoe Association of Realtors Multiple Listing Service.
      <?php endif; ?>
      All rights reserved.</p>
      <img src="<?php echo IDXConfig::imageDir(); ?>/broker-reciprocity.jpg" width="100"/><br />
      <p>All Information Is Deemed Reliable But Is Not Guaranteed Accurate.</p>
      <p>IDX feed powered by <a href="http://www.idxgamechanger.com" target="_blank">IDX GameChanger</a></p>
    </small>
  </div>
  
  
   <div class="reveal" id="idxgcListingInquiry" data-reveal>
     
     <?php echo $idxUI->clientRequestForm($type = "listing-inquiry", $listingId, $height = 550, $popup = true); ?>
     <div class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
     </div>
   </div>
   
   <div class="reveal" id="idxgcSeeItNow" data-reveal>
     <?php echo $idxUI->clientRequestForm($type = "see-it-now", $listingId, $height = 550, $popup = true); ?>
     <div class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
     </div>
   </div>
 
  <script>
    jQuery(document).foundation();
    idxgc.tracking.listingView(document.getElementById("listing_id").value);
  </script>
</div>
<?php endif; ?>

<?php
}

function idxgcSubscribePopup($header = "") {
  global $idxUI;
  ?>
  <div class="reveal tiny" id="idxgcSubscribe" data-reveal>
    <div class="idxgc-subscribe-header">
      <h1>Welcome!</h1>
      <?php if ($header != "") : ?>
        <p><?php echo $header; ?></p>
      <?php else: ?>
        <p>I'd like to add you to my list of valued clients who receive monthly updates on local market conditions and events.</p>
      <?php endif; ?>
    </div>  
    <?php echo $idxUI->clientRequestForm($type = "subscribe", $listingId, $height = 300, $popup = true); ?>
    <div class="close-button" data-close aria-label="Close modal" type="button">
      <span aria-hidden="true">&times;</span>
    </div>
  </div>
  <button style="display:none;" id="idxgc-subscribe-button" class="idxgc-button" data-open="idxgcSubscribe">Subscribe</button> 
  <script>
    jQuery(document).foundation();
    
    if (typeof localStorage.idxgcDateVisited == 'undefined') {
      // Show popup if first visit
      localStorage.idxgcDateVisited = new Date();
      jQuery("#idxgc-subscribe-button").click();
    }
    else {
      // Show popup if has been at least 14 days since last visit
      var currentDate = new Date();
      currentDate.setDate(currentDate.getDate() - 14);
      var lastDateVisited = new Date(localStorage.idxgcDateVisited);
      if (currentDate > lastDateVisited) {
        jQuery("#idxgc-subscribe-button").click();
        localStorage.idxgcDateVisited = new Date(); 
      }
    }
  </script>
  <?php
}

function idxgcCaptureListingWatchPopup() {
  global $idxUI;
  ?>
  <div class="reveal tiny" id="idxgcCaptureListingWatch" data-reveal>
     <?php echo $idxUI->captureListingWatchForm($height = 450, $popup = true); ?>
     <div class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
     </div>
  </div>
  <button style="display:none;" id="idxgc-capture-lw-button" class="idxgc-button" data-open="idxgcCaptureListingWatch">Capture Listing Watch</button> 
  <script>
    jQuery(document).foundation();
    
    if (localStorage.idxgcMlsSearches >= 3) {
      jQuery("#idxgc-capture-lw-button").click();
      localStorage.idxgcMlsSearches = 0;
    }
  </script>
  <?php
}

function idxgcSearchForm($action = "", $searchFormType = "", $excludeLocations = array()) {
  global $idxUI;
  echo $idxUI->searchForm($action, $searchFormType, $excludeLocations);
}


function idxgcSearchResults($listings = array(), $totalResultsCount = 0, $showPagination = true, $showHeader = true, $randomize = false, $limit = 0) {
  global $idxClient, $idxUI;
    
  if ($randomize == true)
    shuffle($listings);    
  
  if (count($listings) == 0 && $totalResultsCount == 0) {
    $errorMessage = "";
    $resultView = "grid";
    if ($_REQUEST["result_view"]) {
      $resultView = $_REQUEST["result_view"];
    }
    try {  
      if ($resultView == "map") {
        $searchParams = $idxClient->searchParams($resultsPerPage = 0);
        $results = $idxClient->getListings($searchParams);
      }
      else {
        $searchParams = $idxClient->searchParams($resultsPerPage = 12);
        $results = $idxClient->getListings($searchParams);
      }
      $listings = array();
      $totalResultsCount = 0;
      if (count($results->listings) > 0) {
        $listings = $results->listings;
        $totalResultsCount = $results->count;
      }
    }
    catch (Exception $ex) {
      $errorMessage = $ex->getMessage();
    }
  }
  
  ?>
  
<div id="idxgc-search-results" class="grid-g">
  <div class="grid-u-1">
    <?php if ($showHeader == true) : ?>
    <div id="idxgc-search-results-header" class="grid-g">
      <div class="grid-u-1 grid-u-sm-1-2">
        <div id="idxgc-search-type-toggle">
        <?php 
          $requestParams = http_build_query($_REQUEST);
          $requestParams = preg_replace("/pg=(\d{1}|\d{2}|\d{3})&/","", $requestParams);
          $requestParams = preg_replace("/pg=(\d{1}|\d{2}|\d{3})/","", $requestParams);
        ?>
         <a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?') . "/?" . $requestParams; ?>&result_view=grid"><span <?php if ($_REQUEST["result_view"] != "map"): ?>class="active"<?php endif; ?>>Grid View</span></a>
         <a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?') . "/?" . $requestParams; ?>&result_view=map"><span <?php if ($_REQUEST["result_view"] == "map"): ?>class="active"<?php endif; ?>>Map View</span></a>
        </div>
      </div>
      <div class="grid-u-1 grid-u-sm-1-2">
        <?php if ($_REQUEST["result_view"] != "map" && $showPagination == true) : ?>
          <?php echo $idxUI->searchPagination($resultsPerPage = 12, $totalResultsCount); ?>
        <?php else: ?>
          <?php echo $idxUI->searchResultsCount($resultsCount = count($listings), $totalCount = $totalResultsCount); ?>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
    
    <?php if ($_REQUEST["result_view"] == "map") : ?>
    <div id="idxgc-map-results">
      <?php $pinsImagePath = trailingslashit( IDXGC_PLUGIN_URI ) . "idxgc/images"; ?>
      <?php 
        
        $searchFormData = get_option('idxgcSearchFormData');
        $mapBounds = array();
        if ($searchFormData) {
          foreach ($searchFormData->map_bounds as $key => $value) {
            $mapBounds[$key] = $value;
          }
        }
      ?>
      
      <?php echo $idxUI->mapResults($listings, $pinsImagePath, $mapBounds); ?>
    </div>
    <?php else: ?>
    <div id="idxgc-list-results">
      <?php echo $idxUI->searchResults($listings, $limit); ?>
       
      <?php if ($showPagination == true) : ?>
      <div id="idxgc-pagination-footer"><?php echo $idxUI->searchPagination($resultsPerPage = 12, $totalResultsCount); ?></div>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php

}

function idxgcListingWatchCapture($clientPortalPath = "") {
  ?>
  <a id="idxgc-lw-capture-popup" href="#" data-open="idxgc-lw-capture" style="display:none;">Listing Watch Capture</a>

<div class="reveal" id="idxgc-lw-capture" data-reveal>
  <h2>Get Notified?</h2>
  <p>Save your search results and receive notifications when new properties match your criteria</p>
  <a href="<?php echo $clientPortalPath; ?>?listing_watch=1"><button class="idxgc-button">Sign Up</button></a>
  <div class="close-button" data-close aria-label="Close modal" type="button">
    <span aria-hidden="true">&times;</span>
  </div>
</div>

<script>
    jQuery(document).foundation();
    jQuery(document).ready(function($) {
      $("#idxgc-lw-capture-popup").click();
    })
</script>
<?php
}

function idxgcContactForm() {
  ob_start();
  global $idxUI;
  echo $idxUI->clientRequestForm($type = "basic-contact");
  $output_string = ob_get_contents();
  ob_end_clean();
  return $output_string;
}

// IDXGC Meta SEO Functions
function idxgcMetaTitle() {
  
  $pageMetaTitle = "";
  $pageTemplate = basename(get_page_template());
  
  if ($pageTemplate == "idxgc-listing.php") {
    global $idxClient;
       
    $mlsNumber = $idxClient->getMlsNumberFromCurrentUrl();
    
    try {
      $listing = $idxClient->getListing($mlsNumber);
      
      $community = $listing->community;
      if ($community == "" || $community == NULL) 
        $community = $listing->state;
      $pageMetaTitle = $community . ' | ' . 
                       $listing->address_1 . ', ' . 
                       $listing->city . ', ' . 
                       $listing->state . ' ' . 
                       $listing->zip; 
    }
    catch(Exception $e) {
      $pageMetaTitle = "Listing not found";
    }
  }
  else {
    $pageMetaTitle = idxgcCmsMetaTitle();
  }
  return $pageMetaTitle;
}

function idxgcCmsMetaTitle()
{
  global $post, $page, $paged;
   
  $pageMetaTitle = get_post_meta( $post->ID, '_sdbx_meta_title', true );
    
  if ( '' !== $pageMetaTitle ) {
    return $pageMetaTitle;
  }
  else {

    wp_title( '|', true, 'right' );
  
    // Add the blog name.
    //bloginfo( 'name' );
  
    // Add the blog description for the home/front page.
    $siteDescription = get_bloginfo( 'description', 'display' );
    if ( siteDescription && ( is_home() || is_front_page() ) )
      return " | $siteDescription";
  
    // Add a page number if necessary:
    if ( $paged >= 2 || $page >= 2 )
      return ' | ' . sprintf( __( 'Page %s', 'sdbx' ), max( $paged, $page ) );
  }
  
}

function idxgcMetaData() {
  $pageTemplate = basename(get_page_template());
  
  if ($pageTemplate == "idxgc-listing.php") {
    global $idxClient;
    try {
      return $idxClient->getListingMetaData($listing);
    }
    catch(Exception $e) {
      return "";
    }
  }
  else {
    if (function_exists('sdbx_meta_data'))
      return sdbx_meta_data();
    else
      return "";  
  }
}

function idxgcCommunity( $atts ) {
  ob_start();
  
  $output = '';
 
  $pull_atts = shortcode_atts( array(
      'list_area' => '',
      'view' => ''
  ), $atts );

  $list_areas = explode(",", $pull_atts["list_area"]);
  $list_areas_trimmed = array();
  
  $view = $pull_atts["view"];
  
  $setting = $pull_atts["setting"];
  
  foreach($list_areas as $list_area) {
    array_push($list_areas_trimmed, trim($list_area));
  }
  
  global $idxClient;
  
  $args = array();
  if (count($list_areas_trimmed) > 0)
    $args["listAreas"] = $list_areas_trimmed;
    
  if ($view != "")
    $args["view"] = $view;
  
  if ($setting != "")
    $args["setting"] = $setting;
  
  $results = $idxClient->getCommunityListings($args);

  echo idxgcSearchResults();
  
  $output_string = ob_get_contents();
  ob_end_clean();
  return $output_string;
}


?>