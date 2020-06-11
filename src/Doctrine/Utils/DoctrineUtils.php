<?php


namespace Teebb\CoreBundle\Doctrine\Utils;


use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;


class DoctrineUtils
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Connection
     */
    private $connect;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;

        $connName = $registry->getDefaultConnectionName();
        $this->connect = $registry->getConnection($connName);

        $managerName = $registry->getDefaultManagerName();
        $this->entityManager = $registry->getManager($managerName);
    }

    public function __destruct()
    {
        $this->connect->close();
    }

    /**
     * 修改连接参数,去掉不必要的数据库名等信息,获取数据库连接,用于查询已有数据库列表
     *
     * @param array $params
     * @return Connection
     * @throws DBALException
     */
    public function getTempConnect(array $params)
    {
        if (isset($params['master'])) {
            $params = $params['master'];
        }

        unset($params['dbname'], $params['path'], $params['url']);

        return DriverManager::getConnection($params);
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /**
     * @throws InvalidArgumentException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function createDatabase()
    {
        $params = $this->connect->getParams();

        $hasPath = isset($params['path']);
        $name = $params['dbname'];
        if (!$name) {
            throw new InvalidArgumentException("Connection does not contain a 'path' or 'dbname' parameter and cannot be created.");
        }

        $tmpConnection = $this->getTempConnect($params);

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

    /**
     * @throws InvalidArgumentException
     * @throws DBALException
     */
    public function dropDatabase()
    {
        $params = $this->connect->getParams();

        $hasPath = isset($params['path']);
        $name = $params['dbname'];
        if (!$name) {
            throw new InvalidArgumentException("Connection does not contain a 'path' or 'dbname' parameter and cannot be created.");
        }

        $tmpConnection = $this->getTempConnect($params);

        $shouldDropDatabase = in_array($name, $tmpConnection->getSchemaManager()->listDatabases());

        // Only quote if we don't have a path
        if (!$hasPath) {
            $name = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);
        }

        if ($shouldDropDatabase) {
            $tmpConnection->getSchemaManager()->dropDatabase($name);
        }

        $tmpConnection->close();
    }

    /**
     *
     * @param ClassMetadata[] $metadataArray
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function createSchema(array $metadataArray): void
    {
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaManager = $this->entityManager->getConnection()->getSchemaManager();
        foreach ($metadataArray as $metadata) {
            $tableName = $metadata->getTableName();
            if ($schemaManager->tablesExist($tableName)) {
                continue;
            }
            $schemaTool->createSchema([$metadata]);
        }
    }

    /**
     * @param array $classNames
     * @return array
     */
    public function getClassesMetadata(array $classNames): array
    {
        $metadataArray = [];
        foreach ($classNames as $className) {
            $metadata = $this->entityManager->getClassMetadata($className);
            $metadataArray[] = $metadata;
        }
        return $metadataArray;
    }
}