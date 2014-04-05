<?php
namespace MageTest\MagentoExtension\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;


class AdminDashboard extends Page
{
    protected $path = '/admin/dashboard';

    /**
    * @return bool
    */
    public function isOpen()
    {
        return strrpos($this->getSession()->getCurrentUrl(), $this->path) > -1 ;
    }

    public function hasTitle($tile)
    {
        $page = $this->getSession()->getPage();

        $title = $page->find('css', '.head-dashboard');
        expect($title)->toBe($title);
    }
}