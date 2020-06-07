<?php


namespace Teebb\CoreBundle\Tests\Functional\Doctrine;


use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DoctrineTest extends KernelTestCase
{
    private $doctrine;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    protected function setUp()
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $this->doctrine = $container->get('doctrine');
        $this->em = $this->doctrine->getManager();

        $connectionName = $this->doctrine->getDefaultConnectionName();
        $connection = $this->doctrine->getConnection($connectionName);

        $params = $connection->getParams();

        if (isset($params['master'])) {
            $params = $params['master'];
        }

        $hasPath = isset($params['path']);
        $name = $params['dbname'];
        if (!$name) {
            throw new InvalidArgumentException("Connection does not contain a 'path' or 'dbname' parameter and cannot be created.");
        }
        // Need to get rid of _every_ occurrence of dbname from connection configuration and we have already extracted all relevant info from url
        unset($params['dbname'], $params['path'], $params['url']);

        $tmpConnection = DriverManager::getConnection($params);

        $shouldNotCreateDatabase = in_array($name, $tmpConnection->getSchemaManager()->listDatabases());

        // Only quote if we don't have a path
        if (!$hasPath) {
            $name = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }

        if (!$shouldNotCreateDatabase) {
            $tmpConnection->getSchemaManager()->createDatabase($name);
        }

        $tmpConnection->close();

    }


    public function testCreateSchema()
    {
        $this->assertTrue(true);

    }

}