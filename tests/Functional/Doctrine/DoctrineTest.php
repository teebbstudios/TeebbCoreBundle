<?php


namespace Teebb\CoreBundle\Tests\Functional\Doctrine;


use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Doctrine\ORM\EntityManager;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Teebb\CoreBundle\Doctrine\Utils\DoctrineUtils;
use Teebb\CoreBundle\Entity\Types\CommentType;
use Teebb\CoreBundle\Entity\Types\ContentType;
use Teebb\CoreBundle\Entity\Types\TaxonomyType;


class DoctrineTest extends KernelTestCase
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var DoctrineUtils
     */
    private $doctrineUtils;

    private $param;

    protected function setUp()
    {
        $kernel = self::bootKernel();
        $container = $kernel->getContainer();

        $doctrine = $container->get('doctrine');
        $this->em = $doctrine->getManager();

        $this->param = $doctrine->getConnection()->getParams();

        $this->doctrineUtils = $container->get('teebb.core.orm.doctrine_utils');

    }

    /**
     * @group create
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function testCreateDatabase()
    {
        $this->doctrineUtils->createDatabase();

        $tempConnection = $this->doctrineUtils->getTempConnect($this->param);

        $databases = $tempConnection->getSchemaManager()->listDatabases();

        $this->assertContains('teebb_core_bundle_test', $databases);

        $tempConnection->close();
    }

    /**
     * @group drop
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function testDropDatabase()
    {
        $this->doctrineUtils->dropDatabase();

        $tempConnection = $this->doctrineUtils->getTempConnect($this->param);

        $databases = $tempConnection->getSchemaManager()->listDatabases();

        $this->assertNotContains('teebb_core_bundle_test', $databases);

        $tempConnection->close();
    }

    /**
     * @group create
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function testCreateTypesSchema()
    {
        $metadataArray = $this->doctrineUtils->getClassesMetadata([ContentType::class, TaxonomyType::class, CommentType::class, Translation::class]);

        $this->doctrineUtils->createSchema($metadataArray);

        $schemaManager = $this->em->getConnection()->getSchemaManager();

        $this->assertContains('teebb_types', $schemaManager->listTableNames());
        $this->assertContains('teebb_taxonomy', $schemaManager->listTableNames());
        $this->assertContains('teebb_comments', $schemaManager->listTableNames());
    }

    /**
     * @group create
     *
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function testCreateTypes()
    {
        $articleType = new ContentType();
        $articleType->setLabel('Article');
        $articleType->setAlias('article');
        $articleType->setDescription('Use articles to post content about time, such as news, news or logs.');
        $articleType->setTranslatableLocale('en_US');

        $pageType = new ContentType();
        $pageType->setLabel('Page');
        $pageType->setAlias('page');
        $pageType->setDescription('Use basic pages for your static content, such as the "About Us" page.');
        $pageType->setTranslatableLocale('en_US');

        $this->em->persist($articleType);
        $this->em->persist($pageType);

        $this->em->flush();

        $typesRepo = $this->em->getRepository(ContentType::class);

        $this->assertCount(2, $typesRepo->findAll());
    }

    /**
     * @group create
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function testCreateTypesTranslation()
    {
        $typesRepo = $this->em->getRepository(ContentType::class);

        $articleType = $typesRepo->findOneBy(['alias' => 'article']);

        $articleType->setLabel('文章');
        $articleType->setDescription('使用文章发布有关时间的内容，如消息，新闻或日志。');
        $articleType->setTranslatableLocale('zh_CN');

        $pageType = $typesRepo->findOneBy(['alias' => 'page']);
        $pageType->setLabel('基本页面');
        $pageType->setDescription('对您的静态内容使用基本页面，比如“关于我们”页面。');
        $pageType->setTranslatableLocale('zh_CN');

        $this->em->persist($articleType);
        $this->em->persist($pageType);

        $this->em->flush();

        /**
         * @var TranslationRepository $translationRepo
         */
        $translationRepo = $this->em->getRepository(Translation::class);

        $articles = $translationRepo->findTranslations($articleType);
        $this->assertCount(2, $articles);

        $pages = $translationRepo->findTranslations($pageType);
        $this->assertCount(2, $pages);

    }
}