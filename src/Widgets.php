<?php
namespace Navi;
use \Exception;

/**
 * Widgets
 */
class Widgets extends Language
{
	/**
	* Display menu.
	* @param classMenu String
	* @param idMenu String
	*/
	public  function menu($params = array() )
	{
		try {
			$menuId = ( $this->config('menuId') ) ? "id = '{$this->config('menuId')}' " : '';
			$menuClass = ( $this->config('menuClass') ) ? "class='{$this->config('menuClass')}' " : '';
			$menuClass = (isset($params['menuClass']) && !empty($params['menuClass'])) ? "class='{$params['menuClass']}' " : $menuClass;
			$menuClassList = (isset($params['menuClassList']) && !empty($params['menuClassList'])) ? $params['menuClassList'] : 'navi-list';
			$menuClassLink = (isset($params['menuClassLink']) && !empty($params['menuClassLink'])) ? $params['menuClassLink'] : 'navi-item';
			$menuSubClass = (isset($params['menuSubClass']) && !empty($params['menuSubClass'])) ? "class='{$params['menuSubClass']}' " : '';
			$menuSubClassList = (isset($params['menuSubClassList']) && !empty($params['menuSubClassList'])) ? "class='{$params['menuSubClassList']}' " : '';
			$menuSubBefore = (isset($params['menuSubBefore']) && !empty($params['menuSubBefore'])) ? $params['menuSubBefore'] : '';
			$menuSubAfter = (isset($params['menuSubAfter']) && !empty($params['menuSubAfter'])) ? $params['menuSubAfter'] : '';
			$lang = ( isset($params['menu']) && !empty($params['menu']) ) ? self::getLang() . '-' . $params['menu']  : self::getLang();
			if($this->menu[$lang]){
				$widget =  "<ul {$menuClass} >";
				foreach ($this->menu[$lang] as $url => $nav) {
					$widget .=  "<li class='{$menuClassList}'>";
					if (self::getPage() == $url) {
						$widget .=  "<span class='active {$menuClassLink}'>{$nav['anchor']}</span>";
					} else {
						$widget .=  "<a class='{$menuClassLink}' href='{$nav['link']}'>{$nav['anchor']}</a>";
					}
					if (isset($nav['sub']) && is_array($nav['sub'])) {
						$widget .=  $menuSubBefore . "<ul {$menuSubClass}>";
						foreach ( $nav['sub'] as $sub_url => $sub_anchor){
							$widget .=  "<li {$menuSubClassList}>";
							if (self::getPage() == $sub_url) {
								$widget .=  "<span class='active'>" . $sub_anchor . "</span>";
							} elseif($url == 'img'){
								$widget .=  $nav['anchor'];
							} else {
								$widget .=  "<a class='sub-item' href='{$this->getDomain()}{$sub_url}'>{$sub_anchor}</a>";
							}
							$widget .=  "</li>";
						}
						$widget .=  "</ul>" . $menuSubAfter;
					}
					$widget .=  "</li>";
				}
				$widget .=  "</ul>";
				echo $widget;
			} else {
				throw new Exception("Отсутствуют данные пунктов меню: \nв масиве Menu или \nв файле настроек. \n Проверьте есть ли недопустимые символы в файле. \n Если есть обрамите их двойными кавычками");
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	/**
	* Navigation.
	* @return mixed
	*/
	public final function navigation($params = array()){
		echo "<div itemscope itemtype='http://schema.org/SiteNavigationElement'>";
		echo "<nav>";
		$this->menu($params);
		echo "</nav>";
		echo "</div>";
	}

	/**
	* Breadcrumbs.
	* @return mixed
	*/
	public final function breadcrumbs($params = array())
	{
		try {
			if($this->menu[self::getLang()]){
				if (!$this->isHome()  && self::getPage() != "/{$this->getLang()}/") {
					foreach ($this->menu as $m) {
						$page = preg_replace("#/$#", "", self::getPage());
						if ( isset($m[$page]) ) {
							$link = $m[$page]['link'];
							$anchor = $m[$page]['anchor'];
						} elseif ( !empty($this->submenu) && array_key_exists( $page, $this->submenu) ) {
							$link = "{$this->getDomain()}{$page}";
							$anchor = "{$this->submenu[$page]}";
						}
					}
					if(isset($link) && !empty($link)){
						$siteName = (isset($params['siteName'])) ? $params['siteName'] : $this->config('siteName');
						$result  = "<div class='breadcrumbs' itemscope itemtype='https://schema.org/BreadcrumbList'>";
						$result .= "
						<div itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'>
							<a href='{$this->getDomain()}'  itemid='{$this->getDomain()}' itemscope itemtype='http://schema.org/Thing' itemprop='item'><span itemprop='name'>{$siteName}</span></a>
							<meta itemprop='position' content='1' />
						</div>
						";
						$result .= ( $this->config('delimiter') ) ? $this->config('delimiter') : '/';
						$result .= "
						<div itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'>
							<span itemscope itemtype='http://schema.org/Thing' itemprop='item' itemid='{$link}'><span itemprop='name'>{$anchor}</span></span>
							<meta itemprop='position' content='2' />
						</div>";
						$result .= "</div>";
						echo $result;
					}
				}
			} else {
				throw new Exception("Отсутствуют данные пунктов меню в файле настроек.");
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	/**
	* Display images whith parametrs
	* @param src String
	* @param params Array
	* @return String
	*/
	public final static function img($src, $params)
	{
		$alt 		= (isset($params['alt'])) ? " alt='{$params['alt']}'" : " alt=''";
		$title 	= (isset($params['title'])) ? " title='{$params['title']}'" : '';
		$class 	= (isset($params['class'])) ? " class='{$params['class']}'" : '';
		$width 	= (isset($params['width'])) ? " width='{$params['width']}'" : '';
		$height = (isset($params['height'])) ? " height='{$params['height']}'" : '';
		echo "<img src='{$src}' {$class} {$alt} {$title} {$width} {$height}>";
	}

	/**
	* Display video whith parametrs from Youtube
	* @param src String
	* @param params Array
	* @return String
	*/
	public final static function video($src, $params)
	{
		$alt 		= (isset($params['alt'])) ? " alt='{$params['alt']}'" : " alt=''";
		$title 	= (isset($params['title'])) ? " title='{$params['title']}'" : '';
		$class 	= (isset($params['class'])) ? " class='{$params['class']}'" : '';
		$width 	= (isset($params['width'])) ? " width='{$params['width']}'" : '';
		$height = (isset($params['height'])) ? " height='{$params['height']}'" : '';
		echo "<iframe {$width} {$height} src='https://www.youtube.com/embed/{$src}' {$class} {$alt} {$title} frameborder='0' allowfullscreen></iframe>";
	}

	/**
	* Display url whith parametrs
	* @param String String String String
	* @return String
	*/
	public function url($src, $anchor, $params)
	{
		$class 	= (isset($params['class'])) ? " class='{$params['class']}'" : '';
		$anchor = (isset($params['anchor'])) ? $params['anchor'] : ltrim(  $params['src'], '/');
		echo "<a {$class} href='{$this->getDomain()}{$src}'>{$anchor}</a>";
	}
	public function getCurrentUrl(){
		foreach ($this->menu[self::getLang()] as $key => $curent) {
			if (self::getPage() == $key) {
				return $key;
			}
		}
	}
	/**
	* Displays language menu
	* @return String
	*/
	public final function lang(){
		$widget = '<ul class="lang">';
		$languages = array_flip($this->languages);
		unset($languages[$this->langCurrent]);
		foreach (array_flip($languages) as $language) {
			$widget .= '<li>';
			if ($this->config('langType') == 'subdomain') {
				$subdomain = explode(".", self::getHost()); //Get subdomain lang
				if ( self::getLang() == $subdomain[0] && self::isHome() ) {
					$href = ($language == $this->langDefault) ? $this->getProtocol() . substr($this->getHost(), 3) : $this->getProtocol() . $language . '.' . substr($this->getHost(), 3);
				} elseif( self::getLang() != $subdomain[0] && self::isHome() ){
					$href = ($language == $this->langDefault) ? $this->getProtocol() . substr($this->getHost(), 3) : $this->getProtocol() . $language . '.' . $this->getHost();
				} else {
					$this->arrIndex = $this->getRealIndex($this->menu[self::getLang()], $this->getPage());
					$nextLangUrl = $this->getNextLangUrl($this->menu[$language], $this->arrIndex);
					if (self::getLang() == $subdomain[0]) {
						$href = ( $language == $this->langDefault ) ? $this->getProtocol() . substr($this->getHost(), 3) . $nextLangUrl : $this->getProtocol() . $language . '.' .  substr($this->getHost(), 3) . $nextLangUrl;
					} else {
						$href = ( $language == $this->langDefault ) ? $this->getProtocol() . substr($this->getHost(), 3) . $nextLangUrl : $this->getProtocol() . $language . '.' .  $this->getHost() . $nextLangUrl;
					}
				}
				$widget .= "<a href='{$href}' rel='alternate' hreflang='{$language}' class='{$language}'>{$language}</a>";
			} elseif( $this->config('langType') == 'default' ) {
				if ( self::getLang() == str_replace(array('/', '\\'), '', self::getPage() ) || self::isHome()) {
					$href = ($language == $this->langDefault) ? $this->getDomain() : "/{$language}";
				} else {
					$this->arrIndex = $this->getRealIndex($this->menu[self::getLang()], $this->getPage());
					$href = $this->getNextLangUrl($this->menu[$language], $this->arrIndex);
				}
				$widget .= "<a href='{$href}' rel='alternate' hreflang='{$language}' class='{$language}'>{$language}</a>";
			}
		}

		$widget .= '</li>';
		$widget .= "<li>";
		$widget .= "<span class='{$this->langCurrent}'> {$this->langCurrent}</span>";
		$widget .= "</li>";
		$widget .= '</ul>';
		echo $widget;
	}

	/**
	* Displays the alternate link (rel="alternate")
	* @return mixed
	*/
	public function getLinkAlternate(){
		$this->arrIndex = $this->getRealIndex($this->menu[$this->lang], $this->getCurrentUrl());

		if (self::getPage() == '/') {
			$link = '<link rel="alternate" hreflang="'.$this->langNext.'" href="http://'.self::getHost().'/'.$this->langNext.'/">';
		} elseif (self::getPage() == "/$this->lang/"){
			$link = '<link rel="alternate" hreflang="'.$this->langNext.'" href="http://'.self::getHost().'/">';
		} else {
			$link = '<link rel="alternate" hreflang="'.$this->langNext.'" href="http://'.self::getHost().$this->getNextLangUrl($this->menu[$this->langNext], $this->arrIndex).'">';
		}
		echo $link;
	}

}

?>