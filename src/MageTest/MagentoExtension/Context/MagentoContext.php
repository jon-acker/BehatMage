<?php
/**
 * BehatMage
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, that is bundled with this
 * package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://opensource.org/licenses/MIT
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email
 * to <magetest@sessiondigital.com> so we can send you a copy immediately.
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context
 *
 * @copyright  Copyright (c) 2012-2014 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\Context;

use Behat\Behat\Context\Context;
use Mage_Core_Model_App as MageApp;
use MageTest\MagentoExtension\Service\ConfigManager,
    MageTest\MagentoExtension\Service\CacheManager,
    MageTest\MagentoExtension\Service,
    MageTest\MagentoExtension\Fixture\FixtureFactory,
    MageTest\MagentoExtension\Service\Session;

use Behat\Gherkin\Node\TableNode;

use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;


/**
 * MagentoContext
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class MagentoContext extends PageObjectContext implements MagentoAwareInterface, Context
{
    /**
     * @var MageApp
     */
    private $app;

    /**
     * @var ConfigManager
     */
    private $configManager;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var FixtureFactory
     */
    private $factory;

    /**
     * @var Session
     */
    private $sessionService;

    /**
     * @Given /^I am logged in as an admin$/
     */
    public function iLoginAsAdminWith()
    {
        $this->getPage('Admin')->open();

        // check if already logged in as admin
        if ($this->getPage('Admin Dashboard')->isOpen()) {
            return;
        }

        $this->getPage('Admin')->login('admin', 'jamesc525');
    }

    /**
     * @When I open admin URI :arg1
     */
    public function iOpenAdminUri($arg1)
    {
        $this->getPage('AdminDashboard')->open();
    }

    /**
     * @Then I should see text :arg1
     */
    public function iShouldSeeText($arg1)
    {
        if ($this->getPage('AdminDashboard')->hasTitle($arg1)) {
            return;
        } else {
            return false;
        }
    }

    /**
     * @Given I am logged in as customer :email identified by :password
     * @Given I log in as customer :email identified by :password
     */
    public function iLogInAsCustomerWithPassword($email, $password)
    {
        $sid = $this->sessionService->customerLogin($email, $password);
        $this->getSession()->setCookie('frontend', $sid);
    }

    /**
     * @Given I am on :page
     * @When I go to :page
     */
    public function iAmOn($page)
    {
        $urlModel = new \Mage_Core_Model_Url();
        $m = explode('/', ltrim($page, '/'));
        if ($this->app->getFrontController()->getRouter('standard')->getRouteByFrontName($m[0])) {
            $this->getSession()->visit($this->locatePath($page));
        } else {
            $xml = <<<CONF
<frontend>
    <routers>
        <{module_name}>
            <use>standard</use>
            <args>
                <module>{module_name}</module>
                <frontName>%s</frontName>
            </args>
        <{module_name}>
    </routers>
</frontend>
CONF;
            $alternate = "Or if you are testing a CMS page ensure the URL is correct and the Page is enabled.";
            $config = sprintf((string) $xml, $m[0]);
            throw new \InvalidArgumentException(
                sprintf(
                    "Missing route for the URI '%s', You should the following XML to your config.xml \n %s \n\n%s",
                    $page,
                    $config,
                    $alternate
                )
            );
        }
    }

    /**
     * @When I set config value for :path to :value in :scope scope
     */
    public function iSetConfigValueForScope($path, $value, $scope)
    {
        $this->configManager->setCoreConfig($path, $value, $scope);
    }

    /**
     * @Given the cache is clean
     * @When I clear the cache
     */
    public function theCacheIsClean()
    {
        $this->cacheManager->clear();
    }

    /**
     * @Given /the following products exist:/
     */
    public function theProductsExist(TableNode $table)
    {
        $hash = $table->getHash();
        $fixtureGenerator = $this->factory->create('product');
        foreach ($hash as $row) {
            if (isset($row['is_in_stock'])) {
                if (!isset($row['qty'])) {
                    throw new \InvalidArgumentException('You have specified is_in_stock but not qty, please add value for qty.');
                };

                $row['stock_data'] = array(
                    'is_in_stock' => $row['is_in_stock'],
                    'qty' => $row['qty']
                );

                unset($row['is_in_stock']);
                unset($row['qty']);
            }

            $fixtureGenerator->create($row);
        }
    }

    public function setApp(MageApp $app)
    {
        $this->app = $app;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function setConfigManager(ConfigManager $config)
    {
        $this->configManager = $config;
    }

    public function setCacheManager(CacheManager $cache)
    {
        $this->cacheManager = $cache;
    }

    public function getCacheManager()
    {
        return $this->cacheManager;
    }

    public function setFixtureFactory(FixtureFactory $factory)
    {
        $this->factory = $factory;
    }

    public function setSessionService(Session $session)
    {
        $this->sessionService = $session;
    }

    public function getSessionService()
    {
        return $this->sessionService;
    }

    public function getFixture($identifier)
    {
        return $this->factory->create($identifier);
    }
}
