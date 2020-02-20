<?php

namespace AppBundle\Tests\Controller;
use Tests\AppBundle\Controller\BaseControllerTest;

class LoginControllerTest extends BaseControllerTest
{
    public $user;
    public $client;
    public function __construct()
    {
        parent::__construct();
        $this->client=static::createClient();
    }

    public function testShowLoginPage()
    {
        $this->client->request('GET', '/login');

        $this->assertContains(
            'Welcome to the Login:login page',
            $this->client->getResponse()->getContent(),
            'Has login text!'
        );
        return "testShowLoginPage";

    }

    public function testShowRegisterPage()
    {
        $this->client->request('GET', '/register');

        $this->assertContains(
            'Register',
            $this->client->getResponse()->getContent(),
            'Has register text!'
        );

    }


    public function testRegister(){

        $crawler = $this->client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();

// set some values
        $form['form[username]'] = 'abc7';
        $form['form[email]'] = 'abc7@aac.com';
        $form['form[password]'] = 'abc7';

// submit the form
        $crawler = $this->client->submit($form);

        $this->assertEquals(
            302,
            $this->client->getResponse()->getStatusCode()
        );
        //redirects to / after register then / redirects to /login
        $this->assertTrue(
            $this->client->getResponse()->isRedirect());

        $this->testLogin('abc7');
    }

}
