<?php

class LTI_Client{
	
	public  $id;
	public  $oauth_clientid;
	private $oauth_secret;
	private $oauth_token=false;
	
	function __construct($id,$oauth_clientid,$oauth_secret,$oauth_token=false) {
	
		$this->id  = htmlspecialchars($id);
		$this->oauth_clientid = sanitize_hash($oauth_clientid);
		$this->oauth_secret = sanitize_hash($oauth_secret);
		if(is_string($oauth_token) && !empty($oauth_token)){
			$this->oauth_token = sanitize_hash($oauth_token);
		}
	
	}
	
	/**
	 * init function.
	 *
	 * Start session and either store or check the existence of required session vars
	 * 
	 * @access public
	 * @return void
	 */
	 
	public function init() {
		
		//Start session to store data
		session_start();
		
		//We check if this is the initial load,  that this is the correct app and that Canvas LMS is returning us required data
		if( isset($_POST) && 
			isset($_POST['custom_canvas_api_domain']) && 
			isset($_POST['custom_canvas_course_id']) && 
			isset($_POST['oauth_consumer_key']) && 
			$_POST['oauth_consumer_key'] === $this->oauth_clientid ){
			
			//We store it in SESSION
			$_SESSION[$this->id.'-api-url'] = $_POST['custom_canvas_api_domain'];
			$_SESSION[$this->id.'-course-id'] = $_POST['custom_canvas_course_id'];
		
		}
		//If it doesn't seem to be the init load we check that we have the data in SESSION or die 
		elseif( !isset($_SESSION[$this->id.'-api-url']) || 
				!isset($_SESSION[$this->id.'-course-id']) ){
		
			die("An issue occurred while loading the app: missing required settings");
		
		}
	
	}
	
	/**
	 * curl_query function.
	 *
	 * A function to handle CURL calls to the multiples APIs we need
	 * 
	 * @access private
	 * @param mixed $url the curl URL
	 * @param array $extra_setopt_array (default: array()) extra curl options to be set
	 * @return array either return the curl response or an error error array
	 */
	 
	private function curl_query( $url , $extra_setopt_array = array() ){
		try{
			
			$curl = curl_init();
			
			//By default we set the minimum Curl options that are required for Canvas LMS calls 
			$setopt_array = array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => filter_var( $url , FILTER_SANITIZE_URL )
			);
			
			curl_setopt_array( $curl , $setopt_array );
	
			
			//If our app want to interact with another API that require more params we can pass them as a param and we are setting then here
			if( is_array( $extra_setopt_array ) && !empty( $extra_setopt_array ) ){
				
				curl_setopt_array( $curl , $extra_setopt_array );
	
			}
			
			$curl_response = curl_exec( $curl );
					
			curl_close( $curl );
			
			// Our API should respond with JSON so we try to decode it in an associative array
			$result = json_decode( $curl_response , true );
			
			//Have we decoded it successfully		
			if(is_array($result) && !empty($result)){
				return $result;
			}
			//If not we display an error message.
			else{
				$error = array( "error" => "API response is not properly formatted" , "response" => print_r($curl_response,true));
				return $error;
			}
			
		}
		catch(Exception $e){
			//If something went wrong we return an error
			$error = array( 
				"error" => "Can't read API response",
				"exception" => print_r($e,true)
			);
			//If the problem is after the curl response is set we include it 
			if (isset($curl_response)){
				$error["response"] = print_r($curl_response,true);
			}
			
			return $error;
		}
		
	
	}
	
	/**
	 * get_header function.
	 * 
	 * Get the app HTML header
	 *
	 * @access public
	 * @param bool|string $title (default: false) Optionnaly display the app title
	 * @return string The HTML header of the app canvas
	 */
	 
	public function get_header($title=false){
		
		$html = '		<!DOCTYPE html>
					<html lang="en">
						<head>
							<meta charset="utf-8">
							<title></title>
							<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
							<script type="text/javascript" src="js/main.js"></script>
							<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/3.0.3/normalize.min.css" type="text/css" />
							<link rel="stylesheet" href="css/style.css" type="text/css" />
						</head>
						<body>
							<section class="app-wrapper '.$this->id.'">';
		if(is_string($title) && !empty($title)){
			$html .= '				<h1 class="page-title">'.htmlspecialchars(strip_tags($title)).'</h1>';			
		}
		
		return $html;
		
	}
	
	/**
	 * display_header function.
	 * 
	 * Display the app HTML header
	 *
	 * @access public
	 * @param bool|string $title (default: false) Optionnaly display the app title
	 * @return void
	 */
	 
	public function display_header($title=false){
		
		echo $this->get_header($title);
		
	}
	
	/**
	 * get_footer function.
	 * 
	 * Get the app HTML footer
	 *
	 * @access public
	 * @param bool|string $copy (default: false) Optionnaly display the app copywrite/footer text
	 * @return void
	 */
	 
	public function get_footer($copy=false){
		
		$html = '				
								<footer>'.htmlspecialchars(strip_tags($copy)).'</footer>
							</section>
						</body>
					</html>';
		
		return $html;
		
	}
	
	/**
	 * display_footer function.
	 * 
	 * Display the app HTML footer
	 *
	 * @access public
	 * @param bool|string $copy (default: false) Optionnaly display the app copywrite/footer text
	 * @return void
	 */
	 
	public function display_footer($copy=false){
		
		echo $this->get_footer($copy);
		
	}
	
	
	/**
	 * canvas_api_query function.
	 * 
	 * Function to handle API calls to the Canvas API
	 * 
	 * @access public
	 * @param mixed $path the API route we are trying to query
	 * @param array $query_params (default: array()) query params that we want to pass to the API
	 * @return array the JSON response as an associative array or an error array
	 */
	 
	public function canvas_api_query( $path , $query_params = array() ){
		
		//Defaults URL params
				
		$params = array( "access_token" => $this->oauth_token );
		
		//Optionnal URL params
		
		if( is_array( $query_params ) && !empty( $query_params ) ){
			$params = array_merge( $params , $query_params );
		}
		
		// We build our CURL URL
		
		$url = 'https://'.filter_var( $_SESSION[$this->id.'-api-url'] , FILTER_SANITIZE_URL ).'/api/v1/'.filter_var( $path , FILTER_SANITIZE_URL ).'?'.http_build_query( $params );
		
		// We call our CURL handling function
		
		return $this->curl_query( $url );
		
	}
	
	
}

?>