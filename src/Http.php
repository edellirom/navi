<?php
namespace Navi;

/**
 * Http
 */
class Http
{

	/**
	* Check if this is "Home page"
	* @return Boolean
	*/
	public final static function isHome()
	{
		return (self::getPage()  == '/') ?  true : false;
	}

	/**
	*  Check if this  page is equally current page
	* @param Array
	* @return Boolean
	*/
	public final static function isPage($pages)
	{
		return in_array( self::getPage(), ( is_array($pages) ) ? $pages : array($pages) );
	}

	/**
	* Returns the current page
	* @return String
 	*/
	public final static function getPage()
	{
		return urldecode(preg_replace('/\?.*/iu', '', getenv('REQUEST_URI')));
	}

	/**
	* Returns protocol
	* @return String
	*/
	public final static function getProtocol()
	{
		try {
			if(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
				return $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://';
			} else {
				return '//';
			}
		} catch (Exception $e) {
		}
	}

	/**
	* Returns domain (Host)
	* @return String
	*/
	public final static function getHost()
	{
		return getenv('HTTP_HOST');
	}

	/**
	* Returns domain  whith protocol
	* @return String
	*/
	public final static function getDomain()
	{
		return self::getProtocol() . self::getHost();
	}

}
?>