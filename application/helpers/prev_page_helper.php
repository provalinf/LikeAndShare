<?php
/**
 * Redirect to previous page if http referer is set. Otherwise to server root.
 */

if (!function_exists('redirect_back')) {
	function redirect_back() {
		if (isset($_SERVER['HTTP_REFERER'])) {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			header('Location: http://' . $_SERVER['SERVER_NAME']);
		}
		exit;
	}
}

if (!function_exists('return_redirect_back')) {
	function return_redirect_back() {
		if (isset($_SERVER['HTTP_REFERER'])) {
			return $_SERVER['HTTP_REFERER'];
		} else {
			return 'http://' . $_SERVER['SERVER_NAME'];
		}
	}
}