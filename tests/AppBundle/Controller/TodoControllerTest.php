<?php

namespace AppBundle\Tests\Controller;
use Tests\AppBundle\Controller\BaseControllerTest;

class TodoControllerTest extends BaseControllerTest
{

    public function testShowTodoPage()
    {
        $this->testLogin('abc4');

        $crawler=$this->client->request('GET', 'http://localhost/profile/abc4/display');

        $this->assertContains(
            'Todos',
            $this->client->getResponse()->getContent()
        );
        $this->assertGreaterThanOrEqual(3, $crawler->filter('tr')->count());
        #return "testShowLoginPage";

        $this->testLogout();
    }

    public function testCreateTodo(){

        $this->testLogin('abc4');

        $crawler=$this->client->request('GET', 'http://localhost:8000/profile/abc4/newtodo');

        $form = $crawler->selectButton('Create Todo')->form();

// set some values
        $form['form[name]'] = 'test11';
        $form['form[description]'] = 'testDesc11';
        $form['form[priority]']='testPrior11';

// submit the form
        $crawler = $this->client->submit($form);

        $this->assertEquals(
            302,
            $this->client->getResponse()->getStatusCode()
        );
        $this->assertTrue(
            $this->client->getResponse()->isRedirect());

        $this->client->request('GET', 'http://localhost/profile/abc4/display');

        $this->assertContains('Description', $this->client->getResponse()->getContent());
        //check if the description of todos is in page
        $this->testLogout();
    }

    public function testDeleteTodo(){

        $this->testLogin('abc4');

        $crawler=$this->client->request('GET', 'http://localhost/profile/abc4/display');

        $link = $crawler
            ->filter('a:contains("Delete")') // find all links with the text "Delete"
            ->eq(0) // select the first link in the list, the one we added recently above
            ->link();

        $crawler= $this->client->click($link);

        $crawler=$this->client->request('GET', 'http://localhost/profile/abc4/display');

        $this->assertGreaterThanOrEqual(3, $crawler->filter('tr')->count());

        $this->testLogout();

    }

}
