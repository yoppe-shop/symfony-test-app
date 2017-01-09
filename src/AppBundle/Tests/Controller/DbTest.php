<?php
namespace Tests\AppBundle\Repository;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Controller\TestController;

class DbTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $tc;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
        $this->tc = new TestController();
    }

    public function testNum()
    {
        $dbTest = $this->tc;
        $result = $dbTest->getNumAttr($this->em, '2');

        $this->assertEquals(2, $result);
    }

    public function testPdo()
    {
        $dbTest = $this->tc;
        $result = $dbTest->pdoQuery($this->em);

        $this->assertEquals(5, count($result));
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }
}