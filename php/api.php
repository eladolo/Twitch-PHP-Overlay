<?php
    include_once("php/defuse-crypto.phar");
    use Defuse\Crypto\Crypto;

	include_once("php/db.php");
    use DB\DB;

    include_once('php/imageCache.php');
    use ImageCache\ImageCache;

	class API{
        private $db;
        private $lang;
        private $private = array();
        protected $protected = array();

        function __construct($db) {
            $this->db = $db;
        }

        /*
        * accept dynamic calls
        */
        public function __call($method, $arguments) {
            $response = false;

            if(isset($this->$method)){
                $response = call_user_func_array(Closure::bind($this->$method, $this, get_called_class()), $arguments);
            }

            return $response;
        }

        /*
        * add new methods to class
        */
        function addMethod($name, $method, $type = "public"){
            switch ($type) {
                case 'private':
                    $this->private[$name] = $method;
                    break;
                case 'protected':
                    $this->protected[$name] = $method;
                    break;
                default:
                    $this->{$name} = $method;
                    break;
            }
        }
	}

    /*
    * Main classes instances
    */
    $Crypto = new Crypto();
    $DB = new DB();
	$API = new API($DB);
    $imgCache = new ImageCache();
    $imgCache->cached_image_directory = $_SERVER["DOCUMENT_ROOT"] . '/img/cached';

    /*
    * Overloading modules
    */
    $extras = scandir('php/api_modules');
    foreach($extras as $file) {
        if(stripos($file, ".php") === false) continue;
        if(stripos($file, ".not.") !== false) continue;
        if(stripos($file, "." . site_req . ".") !== false) continue;
        include_once("php/api_modules/" . $file);
    }

    foreach($extras as $file) {
        if(stripos($file, ".php") === false) continue;
        if(stripos($file, "." . site_req . ".") === false) continue;
        include_once("php/api_modules/" . $file);
    }
