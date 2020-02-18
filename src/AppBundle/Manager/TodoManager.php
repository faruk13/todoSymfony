<?php

namespace AppBundle\Manager;
use AppBundle\Controller\UserController;
use AppBundle\Entity\Todo;
use AppBundle\Repository\TodoRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
#use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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

    public function todoCount($username){
        //$this->repo->totalTodoCountByUser($username);
        $this->repo->totalTodoCountByUser($username);
    }

//    public function hasName($Name)
//    {
//        #returns a list of todos with same name
//        $this->ss->findAllTodosByName($Name);
//        #return $this->findAllTodosByName('$Name');
//
//    }

    public function show($username)
    {
        return $this->repo->allTodosOfUser($username);
    }


}