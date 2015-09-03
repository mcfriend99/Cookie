<?php 

/* 
class xCookie
Handles all thing related to setting, getting and deleting cookies.
 */

/**
 * Configuration for: xCookies
 * Please note: The COOKIE_DOMAIN needs the domain where your app is,
 * in a format like this: .mydomain.com
 * Note the . in front of the domain. No www, no http, no slash here!
 * For local development .127.0.0.1 or .localhost is fine, but when deploying you should
 * change this to your real domain, like '.mydomain.com' ! The leading dot makes the cookie available for
 * sub-domains too.
 * @see http://stackoverflow.com/q/9618217/1114320
 * @see http://www.php.net/manual/en/function.setcookie.php
 *
 * COOKIE_RUNTIME: How long should a cookie be valid ? 1209600 seconds = 2 weeks
 * COOKIE_DOMAIN: The domain where the cookie is valid for, like '.mydomain.com'
 * COOKIE_SECRET_KEY: Put a random value here to make your app more secure. When changed, all cookies are reset.
 */
define("COOKIE_RUNTIME", 1209600);
define("COOKIE_DOMAIN", ".127.0.0.1");
define("COOKIE_SECRET_KEY", "1gp@TMPS{+$78sfpMJFe-92s");


class xCookie {

	
	private $random_token_string = null;
	
	function exists($name){
		
		return isset($_COOKIE[$name]);
		
	}

	function setCookie($name, $value, $location = "/"){
		
		$this->random_token_string = hash('sha256', mt_rand());
		$cookie_string_first_part = $value . ':' . $random_token_string;
        $cookie_string_hash = hash('sha256', $cookie_string_first_part . COOKIE_SECRET_KEY);
        $cookie_string = $cookie_string_first_part . ':' . $cookie_string_hash;
		
		if(setcookie($name, $cookie_string, time() + COOKIE_RUNTIME, $location)){
			return $this->random_token_string;
		} else return false;
		
	}
	
	function deleteCookie($name, $location = '/'){
		
		return setcookie($name, false, time() - (3600 * 3650), $location);
		
	}
	
	
	private function toObject($value){
		
		$pre = @json_encode($value);
		return $pre;
		
	}
	
	
	function getCookieValue($name){
		
		
		list ($cookie_id, $token, $hash) = explode(':', $_COOKIE[$name]);
		
		if ($hash == hash('sha256', $cookie_id . ':' .$token . COOKIE_SECRET_KEY) && !empty($token)) {
			
			return $this->toObject(array("id"=>$cookie_id,"token"=>$token));
		} else {
			return false;
		}
		
	}


}





?>
