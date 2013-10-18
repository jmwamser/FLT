<?php
namespace UCrm\CoreBundle\Twig\Extension;


use UCrm\CoreBundle\EventListener\AuthListener;

/**
 * Twig extension for injecting user into templates
 */
class UserExtension extends \Twig_Extension
{
    /**
     * @var AuthListener 
     */
    protected $auth;

   /**
    * @var \Twig_Environment
    */
    protected $environment;

    public function setAuth(AuthListener $auth = null)
    {
        $this->auth = $auth;
    }

    public function getAuth() 
    {
        return $this->auth;
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getUser()
    {
        $auth = $this->getAuth();
        return isset($auth::$user) ? $auth::$user : null;
    }

    public function getFunctions()
    {
        return array(
            'user' => new \Twig_Function_Method($this, 'getUser'),
        );
    }

    public function getName()
    {
        return 'ucrm_user_twig_extension';
    }
}
