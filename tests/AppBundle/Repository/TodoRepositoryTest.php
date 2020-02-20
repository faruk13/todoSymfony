<?php
namespace Tests\AppBundle\Repository;

use AppBundle\Entity\Todo;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TodoRepositoryTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testTodosByUserName()
    {
        //username taken as 'abc4'
        $todos= $this->entityManager
            ->getRepository(Todo::class)
            ->createQueryBuilder('u')
            ->select('count(DISTINCT t.id)')
            ->from('AppBundle:Todo', 't')
            ->where('t.byUser = :username')
            ->setParameter('username', 'abc4')
            ->getQuery()
            ->getSingleScalarResult();
#has 3 posts for user 'abc4'
        $this->assertGreaterThanOrEqual(3, $todos);
    }

    public function testDeleteUser()
    {
        //registered user added before in controller tests
        //deleting the same user here
        $deleteUser=$this->entityManager
            ->getRepository(User::class)
            ->findOneBy([
                'username'=>'abc7'
            ]);
        #var_dump($deleteUser);die("asasasas");
        if(is_null($deleteUser)==false) {
            $this->entityManager->remove($deleteUser);
            $this->entityManager->flush();
        }

    }
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}

