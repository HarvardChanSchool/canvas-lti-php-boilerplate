<?php



/**
 * hsph_sanitize_hash function.
 *
 * Our app will have user input oauth credentials so we have a function that takes a hash string paremeter and sanitize it
 * 
 * @access public
 * @param string $hash a hash
 * @return string the sanitized hash
 */

if ( !function_exists( 'sanitize_hash' ) ){
	function sanitize_hash($hash){
		return filter_var($hash, FILTER_CALLBACK, ['options' => function($hash) {
	    	return preg_replace('/[^a-zA-Z0-9$\/.]/', '', $hash);
		}]);
	}
}

?>