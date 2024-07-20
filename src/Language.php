<?php
namespace Navi;

/**
 * Language

 */
class Language extends Config
{

	/**
	* Return all langueges
	* @return Array
	*/
	public function getlanguages()
	{
		return $this->languages;
	}

	public function getLangDefault()
	{
		return $this->langDefault;
	}

	public function getLangCurrent()
	{
		return $this->langCurrent;
	}

	// public function getCurrentUrl(){
	// 	foreach ($this->menu[$this->getLang()] as $key => $curent) {
	// 		if (self::getPage() == $key) {
	// 			return $key;
	// 		}
	// 	}
	// }

	/**
	* Check current language
	* @param String
	* @return String
	*/
	public function isLang($lang)
	{
		return (self::getLang() == $lang) ?  true : false;
	}

	public function getNextLangUrl($a,$n){
		$i=0;
		foreach($a as $key => $value)
		{
			if($n==$i) return $key;
			$i++;
		}
	}

	public function getRealIndex($a,$n){
		$i=0;
		foreach($a as $key => $value)
		{
			if($key==$n) return $i;
			$i++;
		}
	}

}
?>