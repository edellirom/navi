<?php
namespace Navi;
use \Exception;
/**
 * Config
 */
class Config extends Http
{

	public $langDefault, $langCurrent;

	protected $config;
	protected $configFile;
	protected $configFileName;
	protected $configFilePath;

	protected $languages;
	protected $menu, $submenu;

	protected $defaultConfigFileName = 'nav.ini';
	protected $defaultMetaFileName = 'meta.ini';



	function __construct( $params = FALSE )
	{
		$this->configFileName = ( isset($params['file']) ) ? $params['file'] : $this->defaultConfigFileName;
		$this->configFilePath = ( isset($params['path']) ) ? $params['path'] : $this->root() . 'config' . DIRECTORY_SEPARATOR;
		$configFile =  $this->configFilePath . $this->configFileName;
		$this->configFile = ( file_exists($configFile) ) ? $this->parseFile( $configFile, true ) : FALSE;
		$this->init();
	}

	/**
	 * INIT
	 */
	public function init()
	{
		try {

			if ($this->configFile() === FALSE) {
				throw new Exception("CONFIG FILE {$this->configFileName} | NOT FOUND", 1);
			} else {
				$file = $this->configFile();

				/* Init config */
				if ( isset($file['config']) && !empty($file['config']) )
					$this->config = $file['config'];

				/* Init  language */
				$this->languages 		=  ( $this->config('lang') ) ? array_map('trim', explode(',', $this->config('lang'))) : array('ru');
				/* Init  links */
				$initMenu = ( $this->config('initMenu') ) ? array_map('trim', explode(',', $this->config('initMenu'))) : [];
				$links 		= array_unique(array_merge($this->languages, $initMenu));

				$this->langDefault 	= $this->languages[0];
				$subLang = explode('.', $this->getHost());
				$urlLang = explode('/', self::getPage());
				if ( isset($subLang[0]) && strlen($subLang[0]) == 2 &&  in_array( $subLang[0], $this->languages) )  {
					$this->langCurrent = trim($subLang[0]);
				} elseif ( isset($urlLang[0]) && strlen($urlLang[0]) == 2 &&  in_array( $urlLang[0], $this->languages) ) {
					$this->langCurrent = trim($urlLang[0]);
				} else {
					$this->langCurrent = $this->langDefault;
				}

				/* Init menu */
				foreach ($links as $lang) {
					foreach ($file[$lang] as $url => $anchor) {
						$this->menu[$lang][$url]['anchor'] = $anchor;
						$this->menu[$lang][$url]['link'] = $this->getDomain() . $url;
						if (array_key_exists( $url, $file)) {
							$this->menu[$lang][$url]['sub'] = $file[$url];
						}
					}
					$submenu = array_column($this->menu[$lang], 'sub');
					foreach($submenu as $subArr){
						foreach($subArr as $key => $val){
							if(isset($arrOut[$key]) && $arrOut[$key] > $val) continue;
							$this->submenu[$key] = $val;
						}
					}
				}
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	/**
	* Return current language
	* @return Stirng
	*/
	public function getLang()
	{
		return $this->langCurrent;
	}

	/**
	 * ROOT PATH
	 */
	public function root($slesh = TRUE)
	{
		$dir = dirname(dirname(dirname(dirname(__DIR__))));
		return ($slesh) ? $dir . DIRECTORY_SEPARATOR : $dir ;
	}

	/**
	 * CONFIG FILE
	 */
	public function configFile(){
		return $this->configFile;
	}

	/**
	* Returns the value of the array configurations.
	* @param String
	* @return String
 	*/
	public function config($config) {
		try {
			return ( isset($this->config[$config]) && !empty($this->config[$config]) ) ? $this->config[$config] : false;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	/**
	* Returns array with meta data.
	* @param String
	* @return String
 	*/
	public function getMeta($fileName = FALSE, $filePath = FALSE){
		try {
			$fileName = ( $fileName == FALSE ) ? $this->defaultMetaFileName : $fileName;
			$filePath = ( $filePath == FALSE ) ? $this->configFilePath : $filePath;
			$metaFile = $filePath . $fileName;
			if (file_exists($metaFile)) {
				return self::parseFile($metaFile, true);
			} else {
				throw new Exception("META FILE {$metaFile} | NOT FOUND");
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	public static function parseFile($file_name, $array = true)
	{
		$data = file($file_name);
		$result = [];
		foreach ($data as $string) {
			preg_match("!\[(.*?)\]!si", $string, $exp_result);
			if (!empty($exp_result) && isset($exp_result[1])) {
				$key = trim($exp_result[1]);
			} elseif(isset($key) && !empty($key)){
				$tmp = explode("=", $string);
				if ( isset($tmp[0]) && !empty($tmp[0]) ) {
					$tmpKey = trim($tmp[0]);
					unset($tmp[0]);
					$tmpString = trim(implode("=", $tmp));
					$tmpString  = ltrim( $tmpString, '"'  );
					$tmpString  = ltrim( $tmpString, '\''  );
					$tmpString  = rtrim( $tmpString, '"'  );
					$tmpString  = rtrim( $tmpString, '\''  );
					if ( isset($tmpKey) && !empty($tmpKey) ) {
						$result[$key][$tmpKey] = $tmpString;
					}
				}
			}
		}
		return $result;
	}
}

function xd($data)
{
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
	die();
}
function xp($data)
{
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
}
?>