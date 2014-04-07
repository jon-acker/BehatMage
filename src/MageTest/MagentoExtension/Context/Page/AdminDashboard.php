<?php
namespace MageTest\MagentoExtension\Context\Page;

use MageTest\MagentoExtension\PageObject\MagentoPage;

class AdminDashboard extends MagentoPage
{
    private $basePath = '/admin/dashboard';

    protected $path = '/admin/dashboard/index/key/{secret}';

    /**
    * @return bool
    */
    public function isOpen()
    {
        return strrpos($this->getSession()->getCurrentUrl(), $this->basePath) > -1 ;
    }

    public function hasTitle($title)
    {
        $page = $this->getSession()->getPage();

        $title = $page->find('css', '.head-dashboard');
    }

}