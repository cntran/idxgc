<?php
  
class IdxUI {
  
  public function listing($listing) {
    ob_start();
    global $idxClient;
    $imageSrc = "https://placeholdit.imgix.net/~text?txtsize=38&txt=&w=300&h=300&txttrack=0";
    if ($listing->picture_count > 0) {
      $imageSrc = $listing->image_path . "/" . $listing->mls_number . "-0.jpg";
    }
    ?>
    
    <div id="idxgc-listing">
      <h1><?php echo "$listing->address_1, $listing->city, $listing->state $listing->zip"; ?></h1>
      <div id="idxgc-listing-image">
        <img src="<?php echo $imageSrc; ?>" />
        <?php if ($listing->status != "ACTIVE") : ?>
          <div id="idxgc-listing-status"><?php echo $listing->status; ?></div>
        <?php endif; ?>
      </div>
      <div id="idxgc-listing-actions">
        <div class="grid-g">
          <div class="grid-u-6-24 action">
            <a href="<?php echo IDXConfig::baseUrl(); ?>/portal?add_favorite=<?php echo $idxClient->getListingId($listing); ?>">
            <img src="<?php echo IDXConfig::imageDir(); ?>/idxgc-listing-favorites-icon.png" />&nbsp;add to favorites
            </a>
          </div>
          <div class="grid-u-6-24 action">
            <a href="mailto:?subject=A Property listing for you&body=<?php echo $listing->address_1; ?> - <?php echo IDXConfig::baseUrl() . "/mls/?mls_number=" . $listing->mls_number; ?>">
            <img src="<?php echo IDXConfig::imageDir(); ?>/idxgc-listing-email-icon.png" />&nbsp;email a friend
            </a>  
          </div>
          <div class="grid-u-6-24 action" onclick="printListingPage('<?php echo IDXConfig::baseUrl(); ?>/idxgc-listing-printable/?mls_number=<?php echo $listing->mls_number; ?>');">
            <img src="<?php echo IDXConfig::imageDir(); ?>/idxgc-listing-print-icon.png" />&nbsp;printer friendly
          </div>
          <div class="grid-u-6-24 action">
            <a class="addthis_button_compact"><img src="<?php echo IDXConfig::imageDir(); ?>/idxgc-listing-share-icon.png" border="0" alt="Share" />&nbsp;share
            </a>
          </div>
        </div>
      </div>
      <div id="idxgc-listing-content">
        <p><?php echo str_replace("�", "", $listing->description); ?></p>
        <h2>Property Details</h2>
        <div class="grid-g">
          <div class="grid-u-1 grid-u-md-10-24">
            <strong>Price:</strong>$<?php echo number_format($listing->list_price); ?><br />
            <strong>Address:</strong><?php echo $listing->address_1; ?><br />
            <strong>City:</strong><?php echo $listing->city; ?><br />
            <strong>State:</strong><?php echo $listing->state; ?><br />
            <strong>Zip:</strong><?php echo $listing->zip; ?><br />
          </div>
          <div class="grid-u-1 grid-u-md-7-24">
            <?php if ($listing->type == "RESIDENTIAL") : ?>
            <strong>Square Feet:</strong><?php echo $listing->square_footage; ?><br />
            <strong>Bedrooms:</strong><?php echo $listing->beds; ?><br />
            <strong>Bathrooms:</strong><?php echo $listing->baths; ?><br />
            <?php endif; ?>
          </div>
          <div class="grid-u-1 grid-u-md-7-24">
            <strong>MLS#:</strong><?php echo $listing->mls_number; ?><br />
            <?php if ($listing->list_type != "") : ?>
            <strong>Type:</strong><?php echo $listing->list_type; ?><br />
            <?php endif; ?>
            <strong>DOM:</strong><?php echo $listing->dom; ?><br />
            <?php if ($listing->virtual_tour != "") : ?>
            <strong><a href="<?php echo $listing->virtual_tour; ?>" target="_blank">Virtual Tour</a></strong><br />
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <input type="hidden" id="listing_id" name="listing_id" value="<?php echo $idxClient->getListingId($listing); ?>" />    
    
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function listingPhotos($listing) {
    if ($listing->picture_count > 0) {
       
      $photos = array();
      for ($i = 0; $i < $listing->picture_count; $i++) {
        $photoUrl = $listing->image_path . "/" . $listing->mls_number . "-" . $i;
        $photos[] = $photoUrl;
      }
      return $photos;
    }
    else {
      return [];
    }
  }
  
  public function searchResultsCount($resultsCount, $totalCount) {
    ob_start();
    global $idxClient;
    
    if ($totalCount > $resultsCount) : ?>
      <div class="idxgc-pagination">
        <em>Displaying maximum of <?php echo $resultsCount; ?> results from total of <?php echo $totalCount; ?>.</em>
      </div>
    <?php else: ?>
      <div class="idxgc-pagination">
        Displaying <?php echo $resultsCount; ?> results.
      </div>
    <?php
    endif;
      
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function searchPagination($resultsPerPage, $resultsCount) {
    ob_start();
    $currentPage = 1;
    if (isset($_REQUEST["pg"])) {
      $currentPage = (int)$_REQUEST["pg"];
    }
    $totalPages = ceil($resultsCount / $resultsPerPage);
    
    $searchParams = http_build_query($_REQUEST);
    $searchParams = preg_replace("/pg=(\d{1}|\d{2}|\d{3})&/","", $searchParams);
    $searchParams = preg_replace("/pg=(\d{1}|\d{2}|\d{3})/","", $searchParams);
    ?>
    <div class="idxgc-pagination">
    Displaying  <?php echo ($currentPage - 1) * $resultsPerPage + 1; ?> - <?php if ($currentPage * $resultsPerPage > $resultsCount * 1) { echo $resultsCount; } else { echo ($currentPage * $resultsPerPage); } ?> of <?php echo $resultsCount; ?> listings.
      <span class="idxgc-pager">
        
        <?php if ($currentPage > 1) : ?>
        <a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?'); ?>/?pg=1&<?php echo $searchParams; ?>">
          <span class="page-button page-button-left">First</span>
        </a>
        <a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?'); ?>/?pg=<?php echo $currentPage - 1; ?>&<?php echo $searchParams; ?>">
          <span class='page-button'><span class='fa fa-chevron-left'></span></span>
        </a>
        <?php else: ?>
          <span class="page-button page-button-left">First</span>
          <span class='page-button'><span class='fa fa-chevron-left'></span></span>
        <?php endif; ?>
    
        <?php if ($currentPage < $totalPages) : ?>
        <a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?'); ?>/?pg=<?php echo $currentPage + 1; ?>&<?php echo $searchParams; ?>">
          <span class='page-button'><span class='fa fa-chevron-right'></span></span>
        </a>
        <a href="<?php echo strtok($_SERVER["REQUEST_URI"],'?'); ?>/?pg=<?php echo $totalPages; ?>&<?php echo $searchParams; ?>">
          <span class="page-button page-button-right">Last</span>
        </a>
        <?php else: ?>
          <span class='page-button'><span class='fa fa-chevron-right'></span></span>
          <span class="page-button page-button-right">Last</span>
        <?php endif; ?>
      </span>
    </div>

    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function listingPermalink($listing) {
    $permalink = IDXConfig::baseUrl() . "/mls/";

    $permalinkSuffix = "$listing->address_1 $listing->city $listing->state $listing->zip";
    $permalinkSuffix = preg_replace('/\s/', "_", $permalinkSuffix);
    $permalinkSuffix = preg_replace('/\-/', "", $permalinkSuffix);
    $permalink .= $permalinkSuffix . "-" . $listing->mls_number;
    return $permalink;
  }

  
  public function searchResults($listings, $limit = 0) {
    ob_start();
    
    $showPriceChange = false;
    if ($_REQUEST["price_updates_since"] || $_REQUEST["price_decreases_since"] || $_REQUEST["price_increases_since"]) {
      $showPriceChange = true;
    }
    
    ?>
    <div id="idxgc-listings">
      <div class="grid-g">
    <?php $count = 1; ?>
    <?php foreach ($listings as $listing) : ?>
      <?php 
        if ($limit > 0) {
          if ($count > $limit)
            break;
        }
      ?>
      <?php
        $imageSrc = "https://placeholdit.imgix.net/~text?txtsize=38&txt=&w=300&h=300&txttrack=0";
        if ($listing->picture_count > 0) {
          $imageSrc = $listing->image_path . "/" . $listing->mls_number . "-0.jpg";
        }
        
        $gridItemClass = "grid-l-item";
        if ($count % 3 == 1)
          $gridItemClass = "grid-l-item";
        else if ($count % 3 == 2)
          $gridItemClass = "grid-c-item";
        else 
          $gridItemClass = "grid-r-item";
      ?>
        <div class="grid-u-1 grid-u-md-1-3 grid-item <?php echo $gridItemClass; ?>">
          <div class="grid-item-content">
            <div class="listing-thumbnail">
              <a href="<?php echo $this->listingPermalink($listing); ?>"><img src="<?php echo $imageSrc; ?>" /></a>
            </div>
            <div class="listing-description">
              <div class="grid-g">
                <div class="grid-u-3-5 address">
                  <span><?php echo $listing->address_1; ?></span>
                </div>
                <div class="grid-u-2-5 price">
                  <span>$<?php echo number_format($listing->list_price); ?></span>
                  <?php if ($showPriceChange) : ?>
                    <?php 
                      $priceChange = $listing->list_price - $listing->list_price_previous;
                      $priceDelta = "";
                      $priceDeltaClass = "idxgc-alert-red";
                      if ($priceChange > 0) {
                        $priceDelta = "+$" . number_format(abs($priceChange));  
                        $priceDeltaClass = "idxgc-alert-green";
                      }
                      else {
                        $priceDelta = "-$" . number_format(abs($priceChange)); 
                        $priceDeltaClass = "idxgc-alert-red";
                      }
                    ?>
                    <div><em class="<?php echo $priceDeltaClass; ?>">(<?php echo $priceDelta; ?>)</em></div>
                  <?php endif; ?>
                </div>
                <div class="grid-u-3-5 details">
                  <?php echo $listing->city; ?>, <?php echo $listing->state; ?><br />
                  <?php if ($listing->type == "RESIDENTIAL") : ?>
                    <?php echo "$listing->beds bed | $listing->baths bath | $listing->square_footage SF"; ?>
                  <?php endif; ?>
                </div>
                <div class="grid-u-2-5 details right">
                  <?php if ($listing->list_type == "Condominium/Townhouse") : ?> 
                      Condo/Townhouse
                  <?php else : ?>
                      <?php echo $listing->list_type; ?>
                  <?php endif; ?>
                  <br />
                  <a href="<?php echo $this->listingPermalink($listing); ?>">View</a>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      <?php $count++; ?>
    <?php endforeach; ?>
      </div>
    </div>
    
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function mapResults($listings, $pinsImagePath, $bounds = array()) {
    ob_start();
    ?>
    <div id="idxgc-map"></div>
    <script>
      var clusterPinUrl = '<?php echo $pinsImagePath; ?>/cluster.png';
      var defaultPinUrl = '<?php echo $pinsImagePath; ?>/residential.png';
      var landPinUrl = '<?php echo $pinsImagePath; ?>/lotsland.png';
      var commercialPinUrl = '<?php echo $pinsImagePath; ?>/commercial.png';
      var businessPinUrl = '<?php echo $pinsImagePath; ?>/businessop.png';
      var incomePinUrl = '<?php echo $pinsImagePath; ?>/income.png';
      var propertyLink = '<?php echo IDXConfig::baseUrl(); ?>/mls/';
      console.log(<?php echo count($bounds); ?>);
      <?php if (count($bounds) > 0 && ($bounds["north"] != 0 || $bounds["east"] != 0 || $bounds["south"] != 0 || $bounds["west"] != 0)) : ?>
        var neEdge = { lat: <?php echo $bounds["north"]; ?>, lng: <?php echo $bounds["east"]; ?> };
        var swEdge = { lat: <?php echo $bounds["south"]; ?>, lng: <?php echo $bounds["west"]; ?> };
      <?php else: ?>
        var neEdge = null;
        var swEdge = null;
      <?php endif; ?>
      
      jQuery(document).ready(function($) {
        initMapWithListings(<?php echo json_encode($listings); ?>);
      });  
    </script>
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function clientPortal() {
    ob_start();
    $add_favorite = $_REQUEST["add_favorite"];
    $listing_watch = $_REQUEST["listing_watch"];
    $market_stats = $_REQUEST["market_stats"];
    $home_valuation = $_REQUEST["home_valuation"];
    $iframeUrl = IdxConfig::HOST_NAME . "clients/login?id=" . IdxConfig::apiKey();
    
    if ($add_favorite && $add_favorite != "") {
      $iframeUrl .= "&add_favorite=" . $add_favorite;
    }
    else if ($listing_watch && $listing_watch != "") {
      $iframeUrl .= "&listing_watch=1";
    }
    if ($market_stats && $market_stats != "") {
      $iframeUrl .= "&market_stats=1";
    }
    if ($home_valuation && home_valuation != "") {
      $iframeUrl = IdxConfig::HOST_NAME . "clients/client_requests/new?id=" . IdxConfig::apiKey() . "&type=home-valuation";
    }
    
    ?>
    <iframe id="idxgc-frame" scrolling="no" style="width:100%;height: 0;" frameborder="0" src="<?php echo $iframeUrl; ?>"></iframe>
    
    <script type="text/javascript">
      idxgc.events.resizeIframeOnLoad();
    </script>    
    
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }  
  
  public function socialWall() {
    ob_start();
    $iframeUrl = IdxConfig::HOST_NAME . "users/social_wall/" . IdxConfig::apiKey();
    ?>
    <iframe id="idxgc-frame" scrolling="no" style="width:100%;height: 0;" frameborder="0" src="<?php echo $iframeUrl; ?>"></iframe>
    
    <script type="text/javascript">
      idxgc.events.resizeIframeOnLoad();
    </script>    
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function clientRequestForm($type, $listingId = "", $height = 450, $popup = false) {
    ob_start();
    
    if ($popup == true) {
      $iframeStyle = "width: 100%; height: ". $height . "px;";
    }
    else {
      $iframeStyle = "width: 100%; height: 100%;";
    }
    ?>
    
    <iframe id="idxgc-frame" class="idxgc-frame" scrolling="yes" style="<?php echo $iframeStyle; ?>" frameborder="0" src="<?php echo IdxConfig::HOST_NAME; ?>clients/client_requests/new?id=<?php echo IdxConfig::apiKey(); ?>&type=<?php echo $type; ?>&listing_id=<?php echo $listingId; ?>"></iframe>
    
    <?php if ($popup == false) : ?>
    <script type="text/javascript">
      idxgc.events.resizeIframeOnLoad();
    </script>    
    <?php endif; ?>
    
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;

  }
  
  public function captureListingWatchForm($height = 400, $popup = false) {
    ob_start();
    
    if ($popup == true) {
      $iframeStyle = "width: 100%; height: ". $height . "px;";
    }
    else {
      $iframeStyle = "width: 100%; height: 100%;";
    }
      
    $searchQueryString = $_SERVER["QUERY_STRING"];
    ?>
    
    <iframe id="idxgc-frame" class="idxgc-frame" scrolling="yes" style="<?php echo $iframeStyle; ?>" frameborder="0" src="<?php echo IdxConfig::HOST_NAME; ?>clients/new_listing_watch?id=<?php echo IdxConfig::apiKey(); ?>&<?php echo $searchQueryString; ?>"></iframe>
    
    <?php if ($popup == false) : ?>
    <script type="text/javascript">
      idxgc.events.resizeIframeOnLoad();
    </script>    
    <?php endif; ?>
    
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function stateRegionAndLocationSelects($searchFormData, $selectFormType = "", $excludeLocations = array()) {
    
    ob_start();
    
    global $idxClient;
    
    $states = $searchFormData->states;
    
    $regionsByState = array();
    $regions = array();
    foreach ($searchFormData->regions as $state => $stateRegions) {
      $regionsByState[$state] = $stateRegions;
      $regions = array_merge($regions, $stateRegions); 
    }
    
    $locationsByState = array();
    foreach ($searchFormData->locations as $state => $regions) {
      $locationsByRegion = array();
      foreach ($regions as $region => $regionLocations) {
        $regionLocations = array_diff($regionLocations, $excludeLocations);
        $locationsByRegion[$region] = $regionLocations;         
      }
      $locationsByState[$state] = $locationsByRegion;
    }

    
    $gridClass = "grid-u-1";
    if ($selectFormType == "advanced") {
      $gridClass = "grid-u-1-3";
    }
  
    ?>
    <div class="<?php echo $gridClass; ?> <?php if ($selectFormType == "advanced") :?>grid-box-left-edge<?php else: ?>grid-box-edge<?php endif;?>">
      <select id="state" name="state">
        <option value="">State</option>
        <?php foreach ($states as $state) : ?>
          <option value="<?php echo $state; ?>" <?php if ($_REQUEST["state"] == $state): ?>selected<?php endif; ?>><?php echo $state; ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="<?php echo $gridClass; ?> <?php if ($selectFormType == "advanced") :?>grid-box<?php else: ?>grid-box-edge<?php endif; ?>">
      <select id="region" name="region" class="region">
        <option value="">Region</option>
      </select>
      <?php foreach ($regionsByState as $regionByStateKey => $regionByStateValue) : ?>
        <select id="region_<?php echo $this->getSlug($regionByStateKey); ?>" name="region_<?php echo $this->getSlug($regionByStateKey); ?>" class="region" style="display: none;">
          <option value="">Region</option>
          <?php foreach ($regionByStateValue as $region) : ?>
            <option value="<?php echo $region; ?>" <?php if ($_REQUEST["region_" . $this->getSlug($regionByStateKey)] == $region): ?>selected<?php endif; ?>><?php echo $region; ?></option>
          <?php endforeach; ?>
        </select>
      <?php endforeach; ?>
    </div>
    <div class="<?php echo $gridClass; ?> <?php if ($selectFormType == "advanced") :?>grid-box-right-edge<?php else: ?>grid-box-edge<?php endif;?>">
      <select id="list_area" name="list_area[]" class="list_area" multiple="multiple">
        <option value="">Location</option>
      </select>
      <?php foreach ($locationsByState as $locationByStateKey => $locationByStateValue) : ?>
        <?php $locationsByRegion = $locationByStateValue; ?>
        <?php foreach ($locationsByRegion as $locationByRegionKey => $locationByRegionValue) : ?>
        
          <?php $listAreaRequest = $_REQUEST["list_area_" . $this->getSlug($locationByStateKey) . "_" . $this->getSlug($locationByRegionKey)]; 
            if (!is_array($listAreaRequest))
              $listAreaRequest = [$listAreaRequest];
            if ($listAreaRequest == NULL)
              $listAreaRequest = [];
          ?>
          <select id="list_area_<?php echo $this->getSlug($locationByStateKey); ?>_<?php echo $this->getSlug($locationByRegionKey); ?>" name="list_area_<?php echo $this->getSlug($locationByStateKey); ?>_<?php echo $this->getSlug($locationByRegionKey); ?>[]" class="list_area" multiple="multiple" style="display: none;">
            <?php foreach ($locationByRegionValue as $location) : ?>
              
              <option value="<?php echo $location; ?>" <?php if (in_array($location, $listAreaRequest)): ?>selected<?php endif; ?>><?php echo $location; ?></option>
            <?php endforeach; ?>
          </select>
        <?php endforeach; ?>
      <?php endforeach; ?>
      
    </div>
    
    <?php
      
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function regionAndLocationSelects($searchFormData, $selectFormType = "", $excludeLocations = array()) {
    ob_start();    
    
    
    $regionsByState = array();
    $regions = array();
    foreach ($searchFormData->regions as $state => $stateRegions) {
      foreach($stateRegions as $region) {
        array_push($regions, $region);
      }
    }

    $regions = array_unique($regions);
    sort($regions);
    
    $locationsByRegion = array();
    foreach ($searchFormData->locations as $state => $stateRegions) {
      foreach ($stateRegions as $region => $regionLocations) {
        $regionLocations = array_diff($regionLocations, $excludeLocations);
        $locationsByRegion[$region] = $regionLocations;
      }
    }
    
    
        
    $gridClass = "grid-u-1";
    if ($selectFormType == "advanced") {
      $gridClass = "grid-u-1-2";
    }
    ?>
    
    <div class="<?php echo $gridClass; ?> <?php if ($selectFormType == "advanced") :?>grid-box-left-edge<?php else: ?>grid-box-edge<?php endif;?>">
        <select id="region" name="region" class="region">
          <option value="">Region</option>
          <?php foreach ($regions as $region) : ?>
            <option value="<?php echo $region; ?>" <?php if ($_REQUEST["region"] == $region): ?>selected<?php endif; ?>><?php echo $region; ?></option>
          <?php endforeach; ?>
        </select>
    </div>
    <div class="<?php echo $gridClass; ?> <?php if ($selectFormType == "advanced") :?>grid-box-right-edge<?php else: ?>grid-box-edge<?php endif;?>">
      <select id="list_area" name="list_area[]" class="list_area" multiple="multiple">
        <option value="">Location</option>
      </select>
      <?php foreach ($locationsByRegion as $locationByRegionKey => $locationByRegionValue) : ?>
        <?php $listAreaRequest = $_REQUEST["list_area_" . $this->getSlug($locationByRegionKey)]; 
          if (!is_array($listAreaRequest))
            $listAreaRequest = [$listAreaRequest];
          if ($listAreaRequest == NULL)
            $listAreaRequest = [];
        ?>
        <select id="list_area_<?php echo $this->getSlug($locationByRegionKey); ?>" name="list_area_<?php echo $this->getSlug($locationByRegionKey); ?>[]" class="list_area" multiple="multiple" style="display: none;">
          <?php foreach ($locationByRegionValue as $location) : ?>
            
            <option value="<?php echo $location; ?>" <?php if (in_array($location, $listAreaRequest)): ?>selected<?php endif; ?>><?php echo $location; ?></option>
          <?php endforeach; ?>
        </select>
      <?php endforeach; ?>
    </div>

    <?php  
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
    
  }
  
   public function locationSelect($searchFormData, $selectFormType = "", $excludeLocations = array()) {
    ob_start();
   
    $locations = array();
    foreach($searchFormData->locations as $state => $stateRegions) {
      foreach($stateRegions as $region => $regionLocations) {
        $locations = array_merge($locations, $regionLocations);
      }
    }
    $locations = array_unique($locations);
    $locations = array_diff($locations, $excludeLocations);
    sort($locations);
    
    ?>
    <div class="grid-u-1 <?php if ($selectFormType == "advanced") : ?>grid-u-md-1-4  grid-box-left-edge<?php else: ?>grid-box-edge<?php endif; ?>">
    <?php $listAreaRequest = $_REQUEST["list_area"]; 
        if (!is_array($listAreaRequest))
          $listAreaRequest = [$listAreaRequest];
        if ($listAreaRequest == NULL)
          $listAreaRequest = [];
      ?>
    <select id="list_area" name="list_area[]" class="list_area" multiple="multiple">
      <?php foreach ($locations as $location) : ?>
        <option value="<?php echo $location; ?>" <?php if (in_array($location, $listAreaRequest)): ?>selected<?php endif; ?>><?php echo $location; ?></option>
      <?php endforeach; ?>
    </select>
    </div>
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function viewSelect($searchFormData, $exclude = array()) {
    ob_start();
    
    $views = array();
    $views = $searchFormData->views;
    $views = array_unique($views);
    $views = array_diff($views, $exclude);
    sort($views);
    
    $viewRequest = $_REQUEST["view"]; 
    if (!is_array($viewRequest))
      $viewRequest = [$viewRequest];
    if ($viewRequest == NULL)
      $viewRequest = [];
    ?>
    
    <select id="view" name="view[]" class="view" multiple="multiple">
      <?php foreach ($views as $view) : ?>
        <option value="<?php echo $view; ?>" <?php if (in_array($view, $viewRequest)): ?>selected<?php endif; ?>><?php echo $view; ?></option>
      <?php endforeach; ?>
    </select>
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function settingSelect($searchFormData, $exclude = array()) {
    ob_start();
    
    $settings = array();
    $settings = $searchFormData->settings;
    $settings = array_unique($settings);
    $settings = array_diff($settings, $exclude);
    sort($settings);
    
    $settingRequest = $_REQUEST["setting"]; 
    if (!is_array($settingRequest))
      $settingRequest = [$settingRequest];
    if ($settingRequest == NULL)
      $settingRequest = [];
    ?>
    
    <select id="setting" name="setting[]" class="setting" multiple="multiple">
      <?php foreach ($settings as $setting) : ?>
        <option value="<?php echo $setting; ?>" <?php if (in_array($setting, $settingRequest)): ?>selected<?php endif; ?>><?php echo $setting; ?></option>
      <?php endforeach; ?>
    </select>
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function searchForm($action, $searchFormType = "", $excludeLocations = array()) {
    ob_start();
    
    if (!is_array($searchFormType) && $searchFormType != "") {
      $searchFormType = array("location" => $searchFormType, "type" => "basic");  
    }
  
    global $idxClient;
    $searchFormData = $idxClient->getSearchFormData();
    
    $searchTypes = $searchFormData->search_types;
    $propertyTypes = array();
    foreach($searchFormData->property_types as $searchType => $searchTypePropertyTypes) {
      $propertyTypes[$searchType] = $searchTypePropertyTypes;
    }
        
    $priceArray = $this->getPriceArray();
    $price = $_REQUEST["price"];
    ?>
    <form action="<?php echo $action; ?>" method="get" class="idxgc-form">
      <div class="grid-g">
        
        <?php
          $gridClass = "grid-u-md-1-3";
          if ($searchFormType["location"] == "state") {
            echo $this->stateRegionAndLocationSelects($searchFormData, $selectFormType = "advanced", $excludeLocations); 
          }
          elseif ($searchFormType["location"] == "region") {
            echo $this->regionAndLocationSelects($searchFormData, $selectFormType = "advanced", $excludeLocations);
          }
          else {
            echo $this->locationSelect($searchFormData, $selectFormType = "advanced", $excludeLocations);
            $gridClass = "grid-u-md-1-4";
          }
        ?>
        
        <div class="grid-u-1 <?php echo $gridClass; ?> <?php if ($gridClass == "grid-u-md-1-3"): ?>grid-box-left-edge<?php else: ?>grid-box<?php endif; ?>">
          
          <select id="search_type" name="search_type">
            <option value="">Search Type</option>
            <?php foreach ($searchTypes as $searchType) : ?>
              <option value="<?php echo $searchType; ?>" <?php if ($_REQUEST["search_type"] == $searchType): ?>selected<?php endif; ?>><?php echo ucfirst(strtolower($searchType)); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="grid-u-1 <?php echo $gridClass; ?> grid-box">
         <select id="property_type" name="property_type" class="property_type">
              <option value="">Property Type</option>
            </select>
            <?php foreach ($propertyTypes as $propertyTypeKey => $propertyTypeValue) : ?>
              <select id="property_type_<?php echo $this->getSlug($propertyTypeKey); ?>" name="property_type_<?php echo $this->getSlug($propertyTypeKey); ?>" class="property_type" style="display: none;">
                <option value="">Property Type</option>
                <?php foreach ($propertyTypeValue as $type) : ?>
                  <option value="<?php echo $type; ?>" <?php if ($_REQUEST["property_type_" . $this->getSlug($propertyTypeKey)] == $type): ?>selected<?php endif; ?>><?php echo $type; ?></option>
                <?php endforeach; ?>
              </select>
            <?php endforeach; ?>
            <input type="hidden" id="list_type" name="list_type" value="<?php echo $_REQUEST["list_type"]; ?>" />
        </div>
        <div class="grid-u-1 <?php echo $gridClass; ?> grid-box-right-edge">
          <select id="price" name="price">
            <option value="">Price</option>
            <option value="250000-500000" <?php if ($price == "250000-500000") : ?>selected<?php endif; ?>>$250K-$500K</option>
            <option value="500000-750000" <?php if ($price == "500000-750000") : ?>selected<?php endif; ?>>$500K-$750K</option>
            <option value="750000-1000000" <?php if ($price == "750000-1000000") : ?>selected<?php endif; ?>>$750K-$1M</option>
            <option value="1000000-1500000" <?php if ($price == "1000000-1500000") : ?>selected<?php endif; ?>>$1M-$1.5M</option>
            <option value="1500000-2000000" <?php if ($price == "1500000-2000000") : ?>selected<?php endif; ?>>$1.5M-$2M</option>
            <option value="2000000-3000000" <?php if ($price == "2000000-3000000") : ?>selected<?php endif; ?>>$2M-$3M</option>
            <option value="3000000-5000000" <?php if ($price == "3000000-5000000") : ?>selected<?php endif; ?>>$3M-$5M</option>
            <option value="5000000-10000000" <?php if ($price == "5000000-10000000") : ?>selected<?php endif; ?>>$5M-$10M</option>
            <option value="10000000" <?php if ($price == "10000000") : ?>selected<?php endif; ?>>$10M+</option>
          </select>
        </div>
       
        <div class="grid-u-1-3 grid-u-md-6-24 grid-box-left-edge">
          <select id="beds" name="beds">
            <option value="">Bedrooms</option>
            <?php for ($i = 6; $i > 0; $i--): ?>
              <option value="<?php echo $i; ?>" <?php if ($_REQUEST["beds"] == $i): ?>selected<?php endif; ?>><?php echo $i; ?>+&nbsp;Bedrooms</option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="grid-u-1-3 grid-u-md-6-24 grid-box">
          <select id="baths" name="baths">
            <option value="">Bathrooms</option>
            <?php for ($i = 6; $i > 0; $i--): ?>
              <option value="<?php echo $i; ?>" <?php if ($_REQUEST["baths"] == $i): ?>selected<?php endif; ?>><?php echo $i; ?>+&nbsp;Bathrooms</option>
            <?php endfor; ?>
          </select>
        </div>
        <div class="grid-u-1-3 grid-u-md-6-24 grid-box">
          <select id="square_footage" name="square_footage">
            <option value="">Square Feet</option>
            <option value="500" <?php if ($_REQUEST["square_footage"] == "500"): ?>selected<?php endif; ?>>500+</option>
            <option value="1000" <?php if ($_REQUEST["square_footage"] == "1000"): ?>selected<?php endif; ?>>1000+</option>
            <option value="2000" <?php if ($_REQUEST["square_footage"] == "2000"): ?>selected<?php endif; ?>>2000+</option>
            <option value="3000" <?php if ($_REQUEST["square_footage"] == "3000"): ?>selected<?php endif; ?>>3000+</option>
            <option value="4000" <?php if ($_REQUEST["square_footage"] == "4000"): ?>selected<?php endif; ?>>4000+</option>
            <option value="5000" <?php if ($_REQUEST["square_footage"] == "5000"): ?>selected<?php endif; ?>>5000+</option>
            <option value="10000" <?php if ($_REQUEST["square_footage"] == "10000"): ?>selected<?php endif; ?>>10000+</option>
          </select>
        </div>
        
        <?php if ($searchFormType["type"] == "advanced") : ?>
           <div class="grid-u-1 grid-u-md-6-24 grid-box-right-edge">
            <select id="year_built" name="year_built">
                <option value="">Year Built</option>
                <?php for ($i = date("Y"); $i > date("Y") - 50; $i--): ?>
                  <option value="<?php echo $i; ?>" <?php if ($_REQUEST["year_built"] == $i): ?>selected<?php endif; ?>>>=&nbsp;<?php echo $i; ?></option>
                <?php endfor; ?>
              </select>
           </div>
        
           <div class="grid-u-1 grid-u-md-6-24 grid-box-left-edge">
              <select name="garage" id="garage">
                <option value="">Garage</option>
                <option value="1" <?php if ($_REQUEST["garage"] == "1"): ?>selected<?php endif; ?> >1+ Car Garage</option>
                <option value="2" <?php if ($_REQUEST["garage"] == "2"): ?>selected<?php endif; ?>>2+ Car Garage</option>
                <option value="3" <?php if ($_REQUEST["garage"] == "3"): ?>selected<?php endif; ?>>3+ Car Garage</option>
                <option value="4" <?php if ($_REQUEST["garage"] == "4"): ?>selected<?php endif; ?>>4+ Car Garage</option>
              </select>
           </div>
           
           <div class="grid-u-1 grid-u-md-6-24 grid-box">
            <?php echo $this->settingSelect($searchFormData, $exclude = array()); ?>
           </div>
           
           <div class="grid-u-1 grid-u-md-6-24 grid-box">
            <?php echo $this->viewSelect($searchFormData, $exclude = array("Panoramic lake", "Panoramic-lake")); ?>
           </div> 
        <?php endif; ?>
                
        <div class="grid-u-1 grid-u-md-6-24 grid-box-right-edge">
          <input type="hidden" id="result_view" name="result_view" value="<?php echo $_REQUEST["result_view"]; ?>" />
          <input id="idxgc-search-button" class="idxgc-search" type="submit" value="Search MLS" />
        </div>
      </div>
    </form>
    <script>
      jQuery(document).ready(function($) {
        $(".list_area").SumoSelect({placeholder: 'Location', prefix: 'Location:', search: true, searchText: 'Select locations (type to search).', selectAll: true});
        $(".view").SumoSelect({placeholder: 'View', prefix: 'View:'});
        $(".setting").SumoSelect({placeholder: 'Setting', prefix: 'Setting:'});
      });
    </script>
    <?
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  public function quickSearchForm($action, $searchFormType = "", $excludeLocations = array()) {
    ob_start();
    
    global $idxClient;
    $searchFormData = $idxClient->getSearchFormData();
    
    $searchTypes = $searchFormData->search_types;
    $propertyTypes = array();
    foreach($searchFormData->property_types as $searchType => $searchTypePropertyTypes) {
      $propertyTypes[$searchType] = $searchTypePropertyTypes;
    }
        
    $priceArray = $this->getPriceArray();
    $price = $_REQUEST["price"];
    
  ?>
    <div class="idxgc-quick-search">
      <h2>MLS Search</h2>
      <div class="idxgc-quick-search-wrapper">
        <form action="<?php echo $action; ?>" method="get" class="idxgc-form">
          <div class="grid-g">
            <?php 
            if ($searchFormType == "state") {
              echo $this->stateRegionAndLocationSelects($searchFormData, $selectFormType = "basic", $excludeLocations); 
            }
            elseif ($searchFormType == "region") {
              echo $this->regionAndLocationSelects($searchFormData, $selectFormType = "basic", $excludeLocations);
            }
            else {
              echo $this->locationSelect($searchFormData, $selectFormType = "basic", $excludeLocations);
              $gridClass = "grid-u-1";
            }
            ?>
            <div class="grid-u-1 grid-box-edge">
              <select id="search_type" name="search_type">
                <option value="">Search Type</option>
                <?php foreach ($searchTypes as $searchType) : ?>
                  <option value="<?php echo $searchType; ?>" <?php if ($_REQUEST["search_type"] == $searchType): ?>selected<?php endif; ?>><?php echo ucfirst(strtolower($searchType)); ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="grid-u-1 grid-box-edge">
             <select id="property_type" name="property_type" class="property_type">
                  <option value="">Property Type</option>
                </select>
                <?php foreach ($propertyTypes as $propertyTypeKey => $propertyTypeValue) : ?>
                  <select id="property_type_<?php echo $this->getSlug($propertyTypeKey); ?>" name="property_type_<?php echo $this->getSlug($propertyTypeKey); ?>" class="property_type" style="display: none;">
                    <option value="">Property Type</option>
                    <?php foreach ($propertyTypeValue as $type) : ?>
                      <option value="<?php echo $type; ?>" <?php if ($_REQUEST["property_type_" . $this->getSlug($propertyTypeKey)] == $type): ?>selected<?php endif; ?>><?php echo $type; ?></option>
                    <?php endforeach; ?>
                  </select>
                <?php endforeach; ?>
            </div>
            <div class="grid-u-1 grid-box-edge">
              <select id="price" name="price">
                <option value="">Price</option>
                <option value="250000-500000" <?php if ($price == "250000-500000") : ?>selected<?php endif; ?>>$250K-$500K</option>
                <option value="500000-750000" <?php if ($price == "500000-750000") : ?>selected<?php endif; ?>>$500K-$750K</option>
                <option value="750000-1000000" <?php if ($price == "750000-1000000") : ?>selected<?php endif; ?>>$750K-$1M</option>
                <option value="1000000-1500000" <?php if ($price == "1000000-1500000") : ?>selected<?php endif; ?>>$1M-$1.5M</option>
                <option value="1500000-2000000" <?php if ($price == "1500000-2000000") : ?>selected<?php endif; ?>>$1.5M-$2M</option>
                <option value="2000000-3000000" <?php if ($price == "2000000-3000000") : ?>selected<?php endif; ?>>$2M-$3M</option>
                <option value="3000000-5000000" <?php if ($price == "3000000-5000000") : ?>selected<?php endif; ?>>$3M-$5M</option>
                <option value="5000000-10000000" <?php if ($price == "5000000-10000000") : ?>selected<?php endif; ?>>$5M-$10M</option>
                <option value="10000000" <?php if ($price == "10000000") : ?>selected<?php endif; ?>>$10M+</option>
              </select>
            </div>
            <div class="grid-u-1 grid-box-edge residential">
              <select id="beds" name="beds">
                <option value="">Bedrooms</option>
                <?php for ($i = 6; $i > 0; $i--): ?>
                  <option value="<?php echo $i; ?>" <?php if ($_REQUEST["beds"] == $i): ?>selected<?php endif; ?>><?php echo $i; ?>+&nbsp;Bedrooms</option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="grid-u-1 grid-box-edge residential">
              <select id="baths" name="baths">
                <option value="">Bathrooms</option>
                <?php for ($i = 6; $i > 0; $i--): ?>
                  <option value="<?php echo $i; ?>" <?php if ($_REQUEST["baths"] == $i): ?>selected<?php endif; ?>><?php echo $i; ?>+&nbsp;Bathrooms</option>
                <?php endfor; ?>
              </select>
            </div>
            <div class="grid-u-1 grid-box-edge residential">
              <select id="square_footage" name="square_footage">
                <option value="">Square Feet</option>
                <option value="500" <?php if ($_REQUEST["square_footage"] == "500"): ?>selected<?php endif; ?>>500+</option>
                <option value="1000" <?php if ($_REQUEST["square_footage"] == "1000"): ?>selected<?php endif; ?>>1000+</option>
                <option value="2000" <?php if ($_REQUEST["square_footage"] == "2000"): ?>selected<?php endif; ?>>2000+</option>
                <option value="3000" <?php if ($_REQUEST["square_footage"] == "3000"): ?>selected<?php endif; ?>>3000+</option>
                <option value="4000" <?php if ($_REQUEST["square_footage"] == "4000"): ?>selected<?php endif; ?>>4000+</option>
                <option value="5000" <?php if ($_REQUEST["square_footage"] == "5000"): ?>selected<?php endif; ?>>5000+</option>
                <option value="10000" <?php if ($_REQUEST["square_footage"] == "10000"): ?>selected<?php endif; ?>>10000+</option>
              </select>
            </div>
            <div class="grid-u-1">
              <input type="hidden" id="result_view" name="result_view" value="<?php echo $_REQUEST["result_view"]; ?>" />
              <p><input class="submit-button" type="submit" value="Search MLS" /></p>
            </div>
          </div>
        </form>
      </div>
    </div>
    <script>
      jQuery(document).ready(function($) {
        $(".list_area").SumoSelect({placeholder: 'Location'});
      });
    </script>
    <?
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  
  function getSlug($string) {
    $string = preg_replace('/\/+/', "-", $string);
    $string = preg_replace('/\s/', "-", $string);
    $string = strtolower($string);
    return $string;
  }
  
  function getFeaturesWidget($clientPortalPath = "") {
    ob_start();
    ?>
    <div id="idxgc-features-widget" class="grid-g">
      <div class="idxgc-feature grid-u-1">
        <a href="<?php echo $clientPortalPath; ?>?market_stats=1"><img src="<?php echo IDXConfig::imageDir(); ?>/idxgc-marketstats.png" />&nbsp;Market Stats</a>
      </div>
      <div class="idxgc-feature grid-u-1">
        <a href="<?php echo $clientPortalPath; ?>?listing_watch=1"><img src="<?php echo IDXConfig::imageDir(); ?>/idxgc-listingwatch.png" />&nbsp;Listing Watch</a>
      </div>
      <div class="idxgc-feature grid-u-1">
        <a href="<?php echo $clientPortalPath; ?>?home_valuation=1"><img src="<?php echo IDXConfig::imageDir(); ?>/idxgc-homevaluation.png" />&nbsp;My Home Valuation</a>
      </div>
    </div>
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }
  
  function getHomeFeaturesWidget($clientPortalPath = "") {
    ob_start();
    ?>
    <div id="idxgc-features-widget" class="idxgc-features-widget-row grid-g">
      <a id="idxgc-feauture-marketstats" class="idxgc-feature grid-u-1 grid-u-sm-1-3" href="<?php echo $clientPortalPath; ?>?market_stats=1">Market Stats</a>
      <a id="idxgc-feauture-homevaluation" class="idxgc-feature grid-u-1 grid-u-sm-1-3" href="<?php echo $clientPortalPath; ?>?home_valuation=1">My Home Valuation</a>
      <a id="idxgc-feauture-listingwatch" class="idxgc-feature grid-u-1 grid-u-sm-1-3" href="<?php echo $clientPortalPath; ?>?listing_watch=1">Listing Watch</a>
    </div>
    
    <style>
      .idxgc-features-widget-row .idxgc-feature {
        padding: 15px;
        padding-left: 75px;
        text-align: left;
        color: #58595B;
      }
      .idxgc-features-widget-row .idxgc-feature:hover {
        color: #FFF;
      }
      #idxgc-feauture-marketstats {
        background: #FFF url(<?php echo IDXConfig::imageDir(); ?>/idxgc-marketstats.png) 15px center no-repeat;
      }
      #idxgc-feauture-marketstats:hover {
        background: #58595B url(<?php echo IDXConfig::imageDir(); ?>/idxgc-marketstats-over.png) 15px center no-repeat;
      }
      #idxgc-feauture-homevaluation {
        background: #FFF url(<?php echo IDXConfig::imageDir(); ?>/idxgc-homevaluation.png) 15px center no-repeat;
      }
      #idxgc-feauture-homevaluation:hover {
        background: #58595B url(<?php echo IDXConfig::imageDir(); ?>/idxgc-homevaluation-over.png) 15px center no-repeat;
      }
      #idxgc-feauture-listingwatch {
        background: #FFF url(<?php echo IDXConfig::imageDir(); ?>/idxgc-listingwatch.png) 15px center no-repeat;
      }
      #idxgc-feauture-listingwatch:hover {
        background: #58595B url(<?php echo IDXConfig::imageDir(); ?>/idxgc-listingwatch-over.png) 15px center no-repeat;
      }

    </style>
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
  }

  
  function getPriceArray() {
    $priceArray = array(10000,20000,30000,40000,50000,60000,70000,80000,90000,100000,200000,300000,400000,500000,600000,700000,800000,900000,1000000,2000000,3000000,4000000,5000000,6000000,7000000,8000000,9000000,10000000);
    return $priceArray;
  }
  
  function getListingWatchUrl() {
    $url = IdxConfig::HOST_NAME . "clients/client_requests/new?type=home-valuation&id=" . IdxConfig::apiKey();
    return $url;
  }
  
  function getMarketStatsUrl() {
    $url = IdxConfig::HOST_NAME . "clients/client_requests/new?type=home-valuation&id=" . IdxConfig::apiKey();
    return $url;
  }
  function getHomeValuationUrl() {
    $url = IdxConfig::HOST_NAME . "clients/client_requests/new?type=home-valuation&id=" . IdxConfig::apiKey();
    return $url;
  }
  
  function fileExists($file) {
    $file_headers = @get_headers($file);
    if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
      return false;
    }
    else {
      return true;
    }
  }
}

?>