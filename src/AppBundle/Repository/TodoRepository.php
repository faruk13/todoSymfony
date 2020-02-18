<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Todo;
use Doctrine\ORM\EntityRepository;
/**
 * TodoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TodoRepository extends EntityRepository
{
    public function findAllTodosByName($Name)
    {
        return $this->findBy([
            'name' => $Name
        ]);
    }

//    public function allTodosOfUser($username){
//        return $this->createQueryBuilder('u')
//            ->where('u.byUser = :username ')
//            ->orderBy('u.id', 'DESC')
//            ->setParameter($username, $username)
//            ->getQuery()
//            ->getResult();
//    }

    public function allTodosOfUser($username){
        return $this->findBy(
            ['byUser' => $username],
            ['id' => 'DESC']
            );
    }

    public function totalTodoCountByUser($username){
        //return 4;
        return  $this->createQueryBuilder('u')
            ->select('count(DISTINCT t.id)')
            ->from('AppBundle:Todo', 't')
            ->where('t.byUser = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getSingleScalarResult();

            //->getSingleScalarResult();
            //->count();
    }




//    public function deleteTodo($id, $username){
//        return $this->createQueryBuilder('u')
//            ->where('u.byUser = :username AND u.id = :id')
//            ->setParameter($username, $username)
//            ->setParameter($id, $id)
//            ->getQuery();
////            ->getResult();
//    }

    public function deleteTodo($id, $username){
        return $this->findOneBy([
            'byUser' => $username,
            'id' =>$id
            ]
        );
    }

}
