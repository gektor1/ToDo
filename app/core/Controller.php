<?php

/**
 * The controller class.
 *
 * The base controller for all other controllers. Extend this for each
 * created controller and get access to it's wonderful functionality.
 */
class Controller {

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    
    /**
     *
     * @var EntityManager
     */
    private $entityManager;
    
    private $app;

    public function __construct(App $app) {
        $this->app = $app;
    }
    
    /**
     * Render a view
     *
     * @param string $viewName The name of the view to include
     * @param array  $data Any data that needs to be available within the view
     *
     * @return void
     */
    public function view($viewName, $data = null) {
        // Create a new view and display the parsed contents
        $view = new View($viewName, $data);

        $data['base_url'] = $this->app->getConfig('base_url');
        
        // View makes use of the __toString magic method to do this
        echo $view;
    }

    /**
     * Load a model
     *
     * @param string $model The name of the model to load
     *
     * @return object
     */
    public function model($model) {
        return new $model();
    }

    /**
     * 
     * @return EntityManager
     */
    public function getEntityManager() {
        if (!$this->entityManager) {
            $this->entityManager = new EntityManager($this->app->getConfig('db'));
        }
        return $this->entityManager;
    }
    
    public function method() {
        return $_SERVER['REQUEST_METHOD'];
    }
 
    public function getApiRequestData() {
        $json = file_get_contents("php://input");
        if (strlen($json) > 0)
            return json_decode($json, true);
    }
    
}
