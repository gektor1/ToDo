<?php

/**
 * The application class.
 *
 * Handles the request for each call to the application
 * and calls the chosen controller and method after splitting the URL.
 *
 */
class App {

    /**
     *
     * Application config
     * 
     * @var array 
     */
    protected $config = [];
    
    /**
     * Stores the controller from the split URL
     *
     * @var string
     */
    protected $controller = 'Home';

    /**
     * Stores the method from the split URL
     * @var string
     */
    protected $method = 'index';

    /**
     * Stores the parameters from the split URL
     * @var array
     */
    protected $params = [];

    public function __construct($config) {
        $this->config = $config;
        
        // Get broken up URL
        $url = $this->parseUrl();

        if (!empty($url[0])) {
            $this->controller = ucfirst($url[0]);
            unset($url[0]);
        }

        $this->controller = new $this->controller($this);

        // Has a second parameter been passed?
        // If so, it might be the requested method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];

                unset($url[1]);
            }
        }

        // Set parameters to either the array values or an empty array
        $this->params = $url ? array_values($url) : [];

        // Call the chosen method on the chosen controller, passing
        // in the parameters array (or empty array if above was false)
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Parse the URL for the current request. Effectivly splits it, stores the controller
     * and the method for that controller.
     *
     * @return void
     */
    public function parseUrl() {
        if (isset($_GET['url'])) {
            // Explode a trimmed and sanitized URL by /
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }

    public function getConfig($key = null) {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }
        return $this->config;
    }
    
}
