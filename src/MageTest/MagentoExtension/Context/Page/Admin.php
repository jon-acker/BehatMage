<?php
namespace MageTest\MagentoExtension\Context\Page;

use MageTest\MagentoExtension\PageObject\MagentoPage;

class Admin extends MagentoPage
{
    protected $path = '/admin/{uri}';

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