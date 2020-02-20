<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseControllerTest extends WebTestCase
{
    protected $user;
    protected $client;
    public function __construct()
    {
        parent::__construct();
        $this->client=static::createClient();
    }

    public function testLogin($user="abc4"){

        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Login')->form();

// set some values
        $form['_username'] = $user;
        $form['_password'] = $user;

// submit the form
        $crawler = $this->client->submit($form);

        $this->assertEquals(
            302, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
            $this->client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $this->client->getResponse()->isRedirect('http://localhost/'));

        $this->client->request('GET', '/');
        $text='Hi '.$user;
        $this->assertContains($text, $this->client->getResponse()->getContent());

    }


    public function testLogout(){

        $this->client->request('GET', '/logout');
        $this->assertTrue(
            $this->client->getResponse()->isRedirect('http://localhost/login'),
            'Logout passed');
    }
}
