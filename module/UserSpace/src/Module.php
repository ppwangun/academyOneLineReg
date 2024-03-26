<?php
namespace UserSpace;


use Laminas\Mvc\MvcEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use User\Controller\AuthController;
use Registration\Controller\CountriesController;
use Registration\Controller\OnlineRegController;
use User\Service\AuthManager;
use Laminas\Session\SessionManager;


use Laminas\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }







    

}