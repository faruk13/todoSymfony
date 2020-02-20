<?php


namespace AppBundle\Manager;

use Doctrine\Common\Persistence\ManagerRegistry as Doctrine;

/**
 * Base Manager
 */
class BaseManager
{
    protected $doctrine;
    protected $em;

    /**
     * Constructor
     * @param Doctrine $doctrine - Doctrine
     */
    public function __construct(Doctrine $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
    }

}