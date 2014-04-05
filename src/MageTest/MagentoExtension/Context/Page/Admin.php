<?php
namespace MageTest\MagentoExtension\Context\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class Admin extends Page
{
    protected $path = '/admin';

    /**
     * @param string $username
     * @param string $password
     */
    public function login($username, $password)
    {
        $form = $this->find('css', '#loginForm');

        $form->fillField('login[username]', $username);
        $form->fillField('login[password]', $password);
        $form->pressButton('Login');
    }
}