<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Payment\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Entity\Student;

use Payment\Service\PayementManager;

use Port\Csv\CsvReader;
use Port\Csv\CsvWriter;
use Port\Doctrine\DoctrineWriter;
use Port\Steps\StepAggregator as Workflow;
use Port\Steps\Step\FilterStep;
use Port\Filter\OffsetFilter;
use Port\Reader\ArrayReader;
use Port\Writer\ArrayWriter;
use Port\Steps\Step\ConverterStep;

class IndexController extends AbstractActionController
{
    private $entityManager;
    private $paymentManager;
    public function __construct($entityManager,$paymentManager) {
        $this->entityManager = $entityManager;
        $this->paymentManager = $paymentManager;
    }

    public function indexAction()
    {
        return [];
    }
    
    public function paymentsAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    
    public function paymentdetailstplAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    public function moratoriumsAction()
    {

          $view = new ViewModel([
             
         ]);
        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);

        return $view;            

    }
    
}