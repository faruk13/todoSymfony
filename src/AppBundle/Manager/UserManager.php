<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
#use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Controller\LoginController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
    {      #todo form passed as arg
        $this->em->persist($user);
        $this->em->flush();
    }
}