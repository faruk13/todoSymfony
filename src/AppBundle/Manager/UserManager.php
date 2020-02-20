<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;

/**
 * User Manager
 */
class UserManager extends BaseManager
{
    protected $repository;

    public function __construct(Doctrine $doctrine)
    {
        parent::__construct($doctrine);
        $this->repository = $this->em->getRepository('AppBundle:User');
    }

    public function delete($id)
    {
        $deleteUser = $this->em->getRepository(User::class)->find($id);
        #deleting by id
        $this->em->remove($deleteUser);
        $this->em->flush();

        return $deleteUser->getUsername();
    }

    public function load($id)
    {
        return $this->em->getRepository(User::class)->find($id);
    }

    public function register($user)
    {      #user detail form passed as arg
        $this->em->persist($user);
        $this->em->flush();
    }
}