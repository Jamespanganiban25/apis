<?php 

namespace App\Services;


class CookieService {
	
	public static function getCookie(string $cookieName) {
		return request()->cookie($cookieName);
	}

	public static function setCookie($name, $value, $minuteExpiration ) {

		$time = time() + (10 * 1000);
		// $time = time() + ($minuteExpiration * 60 * 1000);
		// $time = time() + 3000;

setcookie($name, $value, $time);
		// setcookie("loginAttempts", $value, $time);

//setcookie($name, $value, time() +  ($minuteExpiration * 60));
		/*cookie(
		$name, 
		$value, 
		time() +  ($minuteExpiration * 60)
		); */
	}


}