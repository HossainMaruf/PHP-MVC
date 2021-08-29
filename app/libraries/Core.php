<?php 
	/**
	 * App Core Class
	 * Creates URL & loads core controller
	 * URL FORMAT - /controller/method/params
	 */
	class Core {
		protected $currentController = 'Pages';
		protected $currentMethod = 'index';
		protected $params = [];

		public function __construct() {
			$url = $this->getUrl();
			// Looking controller for first index
			if(file_exists('../app/controllers/'.ucwords($url[0]).'.php')) {
				// if exists, set a current controller
				$this->currentController = ucwords($url[0]);
				// unset 0 index
				unset($url[0]);
			}
			// Require the controller
			require_once('../app/controllers/'.$this->currentController.'.php');
			// Instantiate controller class
			$this->currentController = new $this->currentController();

			// Check second index of URL
			if(isset($url[1])) {
				// Check to see if method exists in controller
				if(method_exists($this->currentController, $url[1])) {
					$this->currentMethod = $url[1];
					// Unset index 1
					unset($url[1]);
				}
			}
			// Get Params
			$this->params = $url ? array_values($url) : [];
			// Call a callback with array of params
			call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

		}

		public function getUrl() {
			if(isset($_GET['url'])) {
				$url = rtrim($_GET['url'], '/');
				$url = filter_var($url, FILTER_SANITIZE_URL);
				return explode('/', $url);
			}
		}
	}
?>