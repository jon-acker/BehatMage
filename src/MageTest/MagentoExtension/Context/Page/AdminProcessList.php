<?php
namespace MageTest\MagentoExtension\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;


class AdminProcessList extends Page
{

    protected $path = '/admin/process/list';

    /**
    * @return bool
    */
    public function isOpen()
    {
        return strrpos($this->getSession()->getCurrentUrl(), $this->path) > -1 ;
    }
}