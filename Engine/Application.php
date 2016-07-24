<?php
namespace API\Engine;
class Application
{
	private $configs;
	private $props;
	private static $request_verison = null;
	public $name = "VN Bean";
	public $version = "1.0";
	private static $instance; 
	public $request; 
	public $database; 
	public $authenticate;
	public function __construct($configs = null)
	{
		self::$instance = $this;
		$this->configs = $configs;
		$this->request = new \API\Engine\Request();
		$this->database = new \API\Engine\Database();
		$this->authenticate = new Authenticate();
	}
	/**
	 * @api {FUNCTION} \API\Engine\Application::getInstance() Get Instance
	 * @apiName get Instance
	 * @apiGroup Application
	 * @apiVersion 1.0.0
	 * @apiDescription Get Instance of current application.
	 */
	public static function getInstance()
	{
		return self::$instance;
	}
	/**
	 * @api {FUNCTION} \API\Engine\Application::getInstance()->db() Get Database
	 * @apiName get Database
	 * @apiGroup Application
	 * @apiVersion 1.0.0
	 * @apiDescription Get Instance of current database connection.
	 */
	public function db()
	{
		return $this->_oDB;
	}
	/**
	 * @api {FUNCTION} \API\Engine\Application::getInstance()->router() Get Router
	 * @apiName get Router
	 * @apiGroup Application
	 * @apiVersion 1.0.0
	 * @apiDescription Get Instance of current router.
	 */
	public function router()
	{
		return $this->_oRouter;
	}
	public function detectApiVersion()
	{
		$seg0 = $this->request->seg(0);
		$aApiConfigs = $this->getConfig('api');
 		$sPatternVersion = isset($aApiConfigs['detect_version'])? $aApiConfigs['detect_version']: ""; 
 		if(!empty($sPatternVersion) && !empty($seg0) && self::$request_verison == null)
 		{
 			preg_match($sPatternVersion, $seg0, $aMatch); 
 			if(isset($aMatch[1]))
 			{
 				self::$request_verison = $aMatch[1];
 			}
 			else
 			{
 				self::$request_verison = "";
 			}
 		}
 		return self::$request_verison;
	}
	public function getConfigs()
	{
		return $this->configs;
	}
	public function getConfig($sName , $mIndex = null)
	{
		$mValues = isset($this->configs[$sName])?$this->configs[$sName]:null; 
		if($mValues)
		{
			if($mIndex)
			{
				return isset($mValues[$mIndex]) ? $mValues[$mIndex] : null;
			}
			return $mValues;
		}
		return null;
	}
	public function getCurrentApiVersion()
	{
		$sVersion = $this->request->getHeaderVersion();
		if(empty($sVersion)) // in case cannot find version api in header params
		{
			$sVersion = $this->detectApiVersion();
		}
		if(empty($sVersion))
		{
			$aVersions = $this->getConfig('api_versions'); 
			$sVersion = $aVersions[count($aVersions) -1];
		}
		return $sVersion;
	}
	public function debug($bEnable = true)
	{
		Debug::register($bEnable);
		return $this;
	}
	public function execute()
	{
		if(isset($this->configs['enviroment']) && $this->configs['enviroment'] == "development")
		{
			$this->debug();
		}
		if(!empty($this->configs['system']['base_path']))
		{
			$this->router->instance()->setBasePath($this->configs['system']['base_path']);
		}
		$sVersionBaseUrl = $this->request->getHeaderVersion();
		if(empty($sVersionBaseUrl)) // in case cannot find version api in header params
		{
			$sVersionBaseUrl = $this->detectApiVersion();
		}
		$sVersion = $sVersionBaseUrl;
		if(!empty($sVersionBaseUrl))
		{
			$this->router->instance()->setBasePath('/'. $this->request->seg(0));
		}else{
			$sVersion = $this->getCurrentApiVersion();
		}
		
		$aMatch = $this->router->match();
		if(isset($aMatch['name']))
		{
			$aRouter = $this->router->getRouter($aMatch['name']);
			
			if(isset($aRouter['route']))
			{
				$aMatch['params']['router'] = $aRouter['route']; 
				$this->request->setParams($aMatch['params']);
				if(isset($aMatch['auth']) && $aMatch['auth'] == true)
				{
					$this->authenticate->auth();
				}
				if( is_callable( $aMatch['target'] ) ) {
					call_user_func_array( $aMatch['target'], $aMatch['params'] );
				} 
				else
				{
					$sController = isset($aRouter['controller'])?$aRouter['controller']:"";
					$sAction = isset($aRouter['action'])?$aRouter['action']:"notfound";
					$sAction = $sAction . "Action";
					$oController = \API\Engine\Object::factory($sController,$sVersion,'controller');
					if($oController && method_exists($oController, $sAction))
					{
						$mResult = $oController->{$sAction}();
						system_display_result($mResult);
					}
					else
					{
						throw new Exception("API NOT FOUND", HTTP_CODE_NOT_FOUND);
					}
				}
			}
			else
			{
				throw new Exception("API NOT FOUND", HTTP_CODE_NOT_FOUND);
			}
			
		}
		else
		{
			throw new Exception("API NOT FOUND", HTTP_CODE_NOT_FOUND);
		}
	}
	public function __get($sName)
	{
		if(isset($this->{$sName}))
		{
			return $this->{$sName};
		}
		$sClassName = ucfirst($sName);
		$sClassName = "API\\Engine\\".$sClassName;
		$this->{$sName} = new $sClassName();
		return $this->{$sName};
	}
	
}