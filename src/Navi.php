<?php
namespace Navi;
use \Exception;

/**
 * Navi
 */
class Navi extends Config
{

	public function __call( $method, array $params)
	{
		try {
			$w = new Widgets;
			if (method_exists($w, $method)) {
				(isset($params[0]) && !empty($params[0])) ? $w->$method($params[0]) : $w->$method();
			} else {
				throw new Exception("Widgets method '{$method}' | NOT FOUND", 1);
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}
}

?>