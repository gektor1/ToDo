<?php

/**
 * The admin controller
 */
class Admin extends Controller {

    /**
     * The default controller method.
     *
     * @return void
     */
    public function index() {
        
        $securityService = new SecurityService($this->getEntityManager());
        
        if ($securityService->authenticated()) {
            $this->view('admin/index');
        } else {
            $this->view('admin/login');
        }
    }

}
