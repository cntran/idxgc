<?php

if ( function_exists( 'idxgcClientPortal' ) )
  add_shortcode( 'idxgcClientPortal', 'idxgcClientPortal' );
if ( function_exists( 'idxgcListing' ) )
  add_shortcode( 'idxgcListing', 'idxgcListing' );
if ( function_exists( 'idxgcSearchResults' ) )
  add_shortcode( 'idxgcSearchResults', 'idxgcSearchResults' );
if ( function_exists( 'idxgcContactForm' ) )
  add_shortcode( 'idxgcContactForm', 'idxgcContactForm' );

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
      <?php endif; ?>
      All rights reserved.</p>
      <img src="<?php echo IDXConfig::imageDir(); ?>/broker-reciprocity.jpg" width="100"/><br />
      <p>All Information Is Deemed Reliable But Is Not Guaranteed Accurate.</p>
      <p>IDX feed powered by <a href="http://www.idxgamechanger.com" target="_blank">IDX GameChanger</a></p>
    </small>
  </div>
  
  
   <div class="small reveal" id="idxgcListingInquiry" data-reveal>
     <?php echo $idxUI->clientRequestForm($type = "listing-inquiry", $listingId, $resize = false, $height = 390); ?>
     <div class="close-button" data-close aria-label="Close modal" type="button">
        <span aria-hidden="true">&times;</span>
     </div>
   </div>
   
   <div class="small reveal" id="idxgcSeeItNow" data-reveal>
     <?php echo $idxUI->clientRequestForm($type = "see-it-now", $listingId, $resize = false, $height = 390); ?>
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

function idxgcSearchForm($action = "", $searchFormType = "") {
  global $idxUI;
  echo $idxUI->searchForm($action, $searchFormType);
}


function idxgcSearchResults() {
  global $idxClient, $idxUI;
    
  $errorMessage = "";
  $resultView = "grid";
  if ($_REQUEST["result_view"]) {
    $resultView = $_REQUEST["result_view"];
  }
  try {  
    if ($resultView == "map") {
      $searchParams = $idxClient->searchParams($resultsPerPage = 0);
      $response = $idxClient->getListings($searchParams);
    }
    else {
      $searchParams = $idxClient->searchParams($resultsPerPage = 12);
      $response = $idxClient->getListings($searchParams);
    }
  }
  catch (Exception $ex) {
    $errorMessage = $ex->getMessage();
  }
  ?>

<div id="idxgc-search-results" class="grid-g">
  <div class="grid-u-1">
    <div class="grid-g">
      <div class="grid-u-1 grid-u-sm-1-2">
        <div id="idxgc-search-type-toggle">
        <?php 
          $requestParams = http_build_query($_REQUEST);
          $requestParams = preg_replace("/pg=(\d{1}|\d{2}|\d{3})&/","", $requestParams);
          $requestParams = preg_replace("/pg=(\d{1}|\d{2}|\d{3})/","", $requestParams);
        ?>
         <a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?') . "/?" . $requestParams; ?>&result_view=grid"><span <?php if ($resultView == "grid"): ?>class="active"<?php endif; ?>>Grid View</span></a>
         <a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?') . "/?" . $requestParams; ?>&result_view=map"><span <?php if ($resultView == "map"): ?>class="active"<?php endif; ?>>Map View</span></a>
        </div>
      </div>
      <div class="grid-u-1 grid-u-sm-1-2">
        <?php if ($resultView == "grid") : ?>
          <?php echo $idxUI->searchPagination($resultsPerPage = 12, $response->count); ?>
        <?php else: ?>
          <?php echo $idxUI->searchResultsCount($resultsCount = count($response->listings), $totalCount = $response->count); ?>
        <?php endif; ?>
      </div>
    </div>
    <?php 
      $listings = array();
      if (count($response->listings) > 0)
        $listings = $response->listings;
    ?>
    <?php if ($resultView == "map") : ?>
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
      
      <?php echo $idxUI->mapResults($response->listings, $pinsImagePath, $mapBounds); ?>
    </div>
    <?php else: ?>
    <div id="idxgc-list-results">
      <?php echo $idxUI->searchResults($response->listings); ?>
      
      <div id="idxgc-pagination-footer"><?php echo $idxUI->searchPagination($resultsPerPage = 12, $response->count); ?></div>
    </div>
    <?php endif; ?>
  </div>
  
</div>
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
    bloginfo( 'name' );
  
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

?>