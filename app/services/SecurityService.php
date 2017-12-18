<?php

class SecurityService {

    /**
     *
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function login($username, $password) {
        $user = $this->getEntityManager()->findBy('User', ['username' => $username, 'password' => md5($password)]);
        if ($user) {
            $_SESSION['user'] = $user->id;
            return true;
        }

        return false;
    }
    
    public function logout() {
        unset($_SESSION['user']);
    }

    public function authenticated() {
        return !empty($_SESSION['user']);
    }
    
    public function getIdentity() {
        if (!$this->authenticated())
            return false;
        
        return $this->getEntityManager()->findBy('User', ['id' => $_SESSION['user']]);
    }

    /**
     * 
     * @return EntityManager
     */
    public function getEntityManager() {
        return $this->entityManager;
    }

}
