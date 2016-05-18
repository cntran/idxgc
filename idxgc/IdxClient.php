<?php

class IdxClient {
  
  const RESULTS_PER_PAGE = 12;
  const MAX_SEARCH_RESULTS = 200;
  
  private $_apikey = '';
  private $_apiversion = 1;
  private $_url = '';
  
  public function __construct() {
    $this->_apikey = IdxConfig::apiKey();
    $this->_apiversion = IdxConfig::API_VERSION;
    $this->_url = IdxConfig::HOST_NAME;
  }
  
  public static function resultsPerPage() {
    return self::RESULTS_PER_PAGE;
  }

  public static function maxSearchResults() {
    return self::MAX_SEARCH_RESULTS;
  }
  
  function getListing($mlsNumber) {
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/' . $mlsNumber
    );
    
    $request = $this->request($args);
    return $request;
  }
  
  function getListingTitle($listing) {
    $title = "$listing->community | $listing->address_1, $listing->city, $listing->state $listing->zip";
    return $title;
  }
  
  function getListingMetaData() {
    
    $mlsNumber = $this->getMlsNumberFromCurrentUrl();
    $listing = $this->getListing($mlsNumber);
    
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $requestUri   = $_SERVER['REQUEST_URI'];
     
    $currentUrl = $protocol . '://' . $host . $requestUri;
    
    $imageSrc = "https://placeholdit.imgix.net/~text?txtsize=38&txt=&w=300&h=300&txttrack=0";
    if ($listing->picture_count > 0) {
      $imageSrc = $listing->image_path . "/" . $listing->mls_number . "-0.jpg";
    }
    $metaTitle = "$listing->community | $listing->address_1, $listing->city, $listing->state $listing->zip";
    
    $metaData  = "<meta name='description' content='" . $listing->description . "' />";
    $metaData .= "<meta property='og:title' content='" . $metaTitle . "' />";
    $metaData .= "<meta property='og:type' content='website' />";
    $metaData .= "<meta property='og:image' content='" . $imageSrc . "' />";   
    $metaData .= "<meta property='og:url' content='" .  $currentUrl . "' />";
    $metaData .= "<meta property='og:description' content='" . $listing->description . "' />";
    
    return $metaData;
  }
  
  function getMlsNumberFromCurrentUrl() {
    $mlsNumber = $_REQUEST["mls_number"];
    if ($mlsNumber == "") {
      $requestUri = rtrim($_SERVER['REQUEST_URI'], "/");
      $requestUri = substr($requestUri,strrpos($requestUri, "/"));
      $requestUri = ltrim($requestUri, "/");
      
      if (strpos($requestUri, '-')) {
        $mlsNumberLength = strlen($requestUri) - strpos($requestUri, '-');
        $mlsNumber = substr($requestUri, strpos($requestUri, '-') + 1, $mlsNumberLength);
      }
      else {
        $mlsNumber = $requestUri;
      }
    }
    return $mlsNumber;      
  }
  
  function getListingId($listing) {
    $listingId = "";
    foreach ($listing->id as $id) {
      $listingId = $id;
      break;
    }
    return $listingId;
  }
  
  function searchParams($resultsPerPage) {
    global $idxUI;
    $resultsStartIndex = 0;
    $currentPage = 1;
    if (isset($_REQUEST["pg"])) {
      $currentPage = (int)$_REQUEST["pg"];
    }
    $resultsStartIndex = ($currentPage - 1) * $resultsPerPage;
    
    if ($resultsPerPage == 0) {
      $resultsPerPage = self::MAX_SEARCH_RESULTS;
    }
     
    $searchType = $_REQUEST["search_type"];
    $listArea = $_REQUEST["list_area"];
    $priceMinimum = $_REQUEST["price_minimum"];
    $priceMaximum = $_REQUEST["price_maximum"];
    $price = $_REQUEST["price"];
    $beds = $_REQUEST["beds"];
    $baths = $_REQUEST["baths"];
    $squareFootage = $_REQUEST["square_footage"];
    $acreageMinimum = $_REQUEST["acreage_minimum"];
    $acreageMaximum = $_REQUEST["acreage_maximum"];
    $listOfficeId = $_REQUEST["list_office_id"];
    $listOfficeName = $_REQUEST["list_office_name"];
    $listAgentEmails = $_REQUEST["list_agent_emails"];
    $view = $_REQUEST["view"];
    $address_1 = $_REQUEST["address_1"];
    $city = $_REQUEST["city"];
    $mlsNumbers = $_REQUEST["mls_numbers"];
     
    $propertyType = $idxUI->getSlug($searchType);
    $listType = $_REQUEST["property_type_$propertyType"];
    
    if ($_REQUEST["state"]) {
      $state = $_REQUEST["state"];
      $stateSlug = $idxUI->getSlug($_REQUEST["state"]);
      $region = $_REQUEST["region_$stateSlug"];
      $location = $idxUI->getSlug($state) . "_" . $idxUI->getSlug($region);
    }
    else {
      $region = $_REQUEST["region"];
      $location = $idxUI->getSlug($region);
    }
    if ($_REQUEST["list_area_$location"] != "") {
      $listArea = $_REQUEST["list_area_$location"];
    }
    $where = array();
    
    if (count($mlsNumbers) > 0) {
      $where["mls_number"] = array("\$in" => $mlsNumbers);
    }
    
    if ($searchType !== "" && $searchType != NULL) {
      $where["type"] = $searchType;
    }
    
    if ($squareFootage !== "" && $squareFootage != NULL) {
      $where["square_footage"] = array("\$gte" => (int)$squareFootage);
    }
    if ($beds !== "" && $beds != NULL) {
      $where["beds"] = array("\$gte" => (int)$beds);
    }
    if ($baths !== "" && $baths != NULL) {
      $where["baths"] = array("\$gte" => (int)$baths);
    }
    if ($state !== "" && $state != NULL) {
      $where["state"] = $state;
    }
    if ($region !== "" && $region != NULL) {
      $where["region"] = $region;
    }
    if ($listArea !== "" && $listArea != NULL) {
      $where["list_area"] = $listArea;
    }
    if ($listType !== "" && $listType != NULL) {
      $where["list_type"] = array("\$in" => [$listType]);
    }
          
    $acreageArray = array();
    if ($acreageMinimum !== "" && $acreageMinimum != NULL)
      $acreageArray["\$gte"] = (float)$acreageMinimum;
    if ($acreageMaximum !== "" && $acreageMaximum != NULL)
      $acreageArray["\$lte"] = (float)$acreageMaximum;
    
    if (count($acreageArray) > 0)
      $where["lot_size"] = $acreageArray;
    
    if ($priceMinimum !== "" && $priceMinimum != NULL) {
      $priceArray["\$gte"] = (int)$priceMinimum;
      $where["list_price"] = $priceArray;
    }
    if ($priceMaximum !== "" && $priceMaximum != NULL) {
      $priceArray["\$lte"] = (int)$priceMaximum;
      $where["list_price"] = $priceArray;
    }
      
    if ($price !== "" && $price != NULL) {
      $prices = explode("-", $price);
      $priceArray = array();
      if (count($prices) > 0) {
        $priceArray["\$gte"] = (int)$prices[0];
      }
      if (count($prices) > 1) {
        $priceArray["\$lte"] = (int)$prices[1]; 
      }
      $where["list_price"] = $priceArray;
    }
    
    if ($listOfficeId !== "" && $listOfficeId != NULL) {
      $where["list_office_id"] = (int)$listOfficeId;
    }
    if ($listOfficeName !== "" && $listOfficeName != NULL) {
      $where["list_office_name"] = $listOfficeName;
    }
    if (count($listAgentEmails) > 0) {
      $where["list_agent_email"] = array("\$in" => $listAgentEmails);
    }
    if ($view !== "" && $view != NULL) {
      $where["view"] = array("\$regex" => $view);
    }
    if ($address_1 !== "" && $address_1 != NULL) {
      $where["address_1"] = array("\$regex" => $address_1);
    }
    if ($city !== "" && $city != NULL) {
      $where["city"] = $city;
    }
    
    $options = array();
    if (count($where) > 0)
      $options["where"] = $where;
    
    $options["limit"] = $resultsPerPage;
    $options["skip"] = $resultsStartIndex;
    $options["sort"] = array("list_price" => -1, "address_1" => 1);
    
    return $options;
  }

  function getListings($options = array()) {
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/'
    );
    if (count($options) > 0) {
      $args['options'] = $options;
    }
    $response = $this->request($args);
   
    return $response;
  }
  
  function getFeaturedListings($args = array("mlsNumbers" => array(), "listAgentEmails" => array(), "listOfficeName" => array()) ) { //$mlsNumbers = array(), $listAgentEmails = array(), $listOfficeName = "") {
    
    $errorMessage = "";

    try {  
      
      $listings = array();
      $featuredListings = array();
      $agentListings = array();
      $officeListings = array();
      
      if (is_array($args["listAgentEmails"]) && count($args["listAgentEmails"]) > 0) {
        $_REQUEST["mls_numbers"] = null;
        $_REQUEST["list_office_name"] = null;
        $_REQUEST["list_agent_emails"] = $args["listAgentEmails"];
        $searchParams = $this->searchParams($resultsPerPage = 0);
        $agentResults = $this->getListings($searchParams);
        $agentListings = $agentResults->listings;
        $listings = array_merge($agentListings, $listings);
      }
      
      if (is_array($args["mlsNumbers"]) && count($args["mlsNumbers"]) > 0) {
        $_REQUEST["list_agent_emails"] = null;
        $_REQUEST["list_office_name"] = null;
        $_REQUEST["mls_numbers"] = $args["mlsNumbers"];
        $searchParams = $this->searchParams($resultsPerPage = 0);
        $featuredResults = $this->getListings($searchParams);
        $featuredListings = $featuredResults->listings;
        $listings = array_merge($featuredListings, $listings);
      }
      
      if ($args["listOfficeName"] != "") {
        $_REQUEST["list_agent_emails"] = null;
        $_REQUEST["mls_numbers"] = null;
        $_REQUEST["list_office_name"] = $args["listOfficeName"];
        $searchParams = $this->searchParams($resultsPerPage = 0);
        $officeResults = $this->getListings($searchParams);
        $officeListings = $officeResults->listings;
        $listings = array_merge($officeListings, $listings);
      }
      
      $listingMlsNumbers = array();
      $listingsToReturn = array();
      foreach ($listings as $listing) {
        if (!in_array($listing->mls_number, $listingMlsNumbers)) {
          array_push($listingsToReturn, $listing);
        }
        $listingMlsNumbers[] = $listing->mls_number;
      }
      
      $results = new stdClass();
      $results->count = count($listingsToReturn);
      $results->listings = $listingsToReturn;
      
      return $results;
    }
    catch (Exception $ex) {
      $errorMessage = $ex->getMessage();
    }
    
  }
  
  function getCommunityListings($args = array("listAreas" => array(), "view" => "")) { 
         
    $listAreasTrimmed = array();
    $listAreas = $args["listAreas"];
    
    foreach($listAreas as $listArea) {
      array_push($listAreasTrimmed, trim($listArea));
    }
    $listAreasTrimmed = array_filter($listAreasTrimmed);
    if ($args["view"] != "")
      $_REQUEST["view"] = $view;
      
    if (count($listAreasTrimmed) > 0)
      $_REQUEST["list_area"] = $listAreasTrimmed;
    
    $_REQUEST["search_type"] = "RESIDENTIAL";
    $_REQUEST["property_type_residential"] = "Single Family";
    
    $searchParams = $this->searchParams($resultsPerPage = self::RESULTS_PER_PAGE);
    
    $results = $this->getListings($searchParams);
    return $results;
 
  }
  
  function getLocations() {
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/locations/' 
    );
    $response = $this->request($args);
    return $response->locations;
  }
  
  function getLocationsByRegion($region) {
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/locations_by_region/' 
    );
    $args['options']['region'] = $region;
    $response = $this->request($args);
    return $response->locations;
  }
  
  function getLocationsByStateAndRegion($state, $region) {
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/locations_by_state_and_region/' 
    );
    $args['options']['state'] = $state;
    $args['options']['region'] = $region;
    $response = $this->request($args);
    return $response->locations;
  }
  
  function getRegions() {
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/regions/' 
    );
    $response = $this->request($args);
    return $response->regions;
  }
  
  function getRegionsByState($state) {
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/regions_by_state/' 
    );
    
    $args['options']['state'] = $state;
    $response = $this->request($args);
    return $response->regions;
  }
  
  function getStates() {
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/states/' 
    );
    $response = $this->request($args);
    
    return $response->states;
  }
  
  function getSearchTypes() {
   
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/search_types/' 
    );
    $response = $this->request($args);
    return $response->search_types;
    
  }
  
  function getPropertyTypes($searchType) {
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/property_types/' 
    );
    $args['options']['search_type'] = $searchType;
    $response = $this->request($args);
    return $response->property_types;
  }
  
  function getSearchFormData() {
    $args = array(
      'method' => 'GET',
      'requestUrl' => 'api/listings/search_form_data/'
    );
    $response = $this->request($args);
    return $response;
  }

  // Handle curl requests...
  public function request($args) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Api-Access-Key: ' . $this->_apikey,
      'Accept: application/vnd.api.v' . $this->_apiversion
    ));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $args['method']);
    $url = $this->_url . $args['requestUrl'];
    
    if ($args['options']) {
      $options = $args['options'];
      unset($options["where"]);
      unset($options["sort"]);
      unset($options["list_type"]);
      $url .= "?" . http_build_query($options);
    }
    else {
      $url .= "?";
    }
    
    $args['options']['where']['status'] = "ACTIVE";
    if (isset($args['options']['where'])) {
      $url .= "&where=" . urlencode(json_encode($args['options']['where']));
    }
    if (isset($args['options']['sort'])) {
      $url .= "&sort=" . urlencode(json_encode($args['options']['sort']));
    }
    if (isset($args['options']['list_type'])) {
      $url .= "&list_type=" . urlencode(json_encode($args['options']['list_type']));
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    
    if ($args['method'] == 'POST' || $args['method'] == 'PUT' || $args['method'] == 'DELETE') {
      curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($args['post_args'])); 
    }
    
    $response = curl_exec($ch);
    $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $expectedCode = array('200','201');
    curl_close($ch);
    
    return $this->checkResponse($response,$responseCode,$expectedCode);
  }
  
  private function checkResponse($response,$responseCode,$expectedCode){
    if(!in_array($responseCode,$expectedCode)){
      throw new Exception($response);
    }
    else{
      //check for empty return
      if($response == '{}'){
        return true;
      }
      else{
        return json_decode($response);
      }
    }
  }
  
}
?>