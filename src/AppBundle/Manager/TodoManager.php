<?php

namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;

/**
 * Todo Manager
 */
class TodoManager extends BaseManager
{
    protected $repo;

    public function __construct(Doctrine $doctrine)
    {
        parent::__construct($doctrine);
        $this->repo = $this->em->getRepository('AppBundle:Todo');

    }

    public function delete($id, $username)
    {
        $deleteTodo = $this->repo->deleteTodo($id, $username);
        #deleting by id
        $this->em->remove($deleteTodo);
        $this->em->flush();
        //returns name of todo deleted

        return $deleteTodo->getName();
    }

    public function create($todo)
    {      #todo form passed as arg
        $this->em->persist($todo);
        $this->em->flush();
    }

    public function todoCount($username)
    {

        return $this->repo->totalTodoCountByUser($username);
    }

    public function show($username, $page_num)
    {
        return $this->repo->allTodosOfUser($username, $page_num-1);
    }


}