<?php

namespace MageTest\MagentoExtension\PageObject;

use Behat\Mink\Session;
use SensioLabs\Behat\PageObjectExtension\Context\PageFactoryInterface;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class MagentoPage extends Page
{
    private $app;

    public function __construct(Session $session, PageFactoryInterface $pageFactory, array $parameters = array())
    {
        $this->app = \Mage::app();

        parent::__construct($session, $pageFactory, $parameters);
    }

    public function open($uri)
    {
        $urlModel = new \Mage_Adminhtml_Model_Url();
        $m = explode('/', ltrim($uri, '/'));
        // Check if frontName matches a configured admin route
        if ($this->app->getFrontController()->getRouter('admin')->getRouteByFrontName($m[0])) {
            $processedUri = "{$m[1]}/{$m[2]}/key/".$urlModel->getSecretKey($m[0], $m[1]);

            $urlParameters = array(
                'uri' => $processedUri
            );
        } else {
            throw new \InvalidArgumentException('$uri parameter should start with a valid admin route and contain controller and action elements');
        }

        parent::open($urlParameters);
    }

} 