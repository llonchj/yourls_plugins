<?php
/*
Plugin Name: Query String Forward
Plugin URI: https://github.com/llonchj/yourls_plugins   
Description: Pushes the Short URL Query String into the Destination URL
Version: 1.0
Author: Jordi Llonch
Author URI: https://github.com/llonchj
*/

// Hook our custom function into the 'pre_redirect' event
yourls_add_filter('redirect_location', 'qs_forward_redirect' );

// Our custom function that will be triggered when the event occurs
function qs_forward_redirect($url, $code) {
    $parsed_url = parse_url($url);

    parse_str($_SERVER['QUERY_STRING'], $query);
    parse_str(isset($parsed_url["query"]) ? $parsed_url["query"] : null, $url_query);
    
    $a = array_merge($query, $url_query);
    $parsed_url["query"] = http_build_query($a);

    $new_url = $parsed_url["scheme"]."://".$parsed_url["host"];
    if (isset($parsed_url["port"]) && $parsed_url["port"] != "")
       $new_url = $new_url.":".$parsed_url["port"];
    $new_url = $new_url.$parsed_url["path"];
    
    if (isset($parsed_url["query"]) && /* fix by XL-Network. Thank you!*/$parsed_url["query"] != "")
        $new_url = "$new_url?".$parsed_url["query"];

    if (isset($parsed_url["fragment"]) && $parsed_url["fragment"] != "")
        $new_url = "$new_url#".$parsed_url["fragment"];
	
    return $new_url;
}

?>