<?php


namespace Teebb\CoreBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Translatable\Entity\Translation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Teebb\CoreBundle\Doctrine\Utils\DoctrineUtils;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;

/**
 * 初始化Schemas
 */
class InitDatabaseCommand extends Command
{
    protected static $commandName = 'teebb:database:init';

    /**
     * @var DoctrineUtils
     */
    private $doctrineUtils;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(DoctrineUtils $doctrineUtils)
    {
        $this->doctrineUtils = $doctrineUtils;
        $this->em = $this->doctrineUtils->getEntityManager();

        parent::__construct();

    }

    public function configure()
    {
        $this->setDescription('Init database.')
            ->addOption('drop', 'd',
                InputOption::VALUE_NONE,
                'Before the initialization, delete the previous database. After the deletion, it cannot be restored. Please proceed with caution.'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\DBAL\Exception\InvalidArgumentException
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = new QuestionHelper();

        if ($input->getOption('drop')) {
            $result = $helper->ask($input, $output,
                new ConfirmationQuestion('Before the initialization, delete the previous database. It cannot be restored. Confirm? (Y/n)'));

            if ($result) {
                $this->doctrineUtils->dropDatabase();
                $output->writeln(sprintf('<info>The database has been deleted.</info>'));
            }
        }

        $this->doctrineUtils->createDatabase();
        $output->writeln(sprintf('<info>The database has been created.</info>'));

        $output->writeln(sprintf('<info>Init tables...</info>'));

        $classMetadataArray = $this->doctrineUtils->getClassesMetadata($this->getMappedClasses());

        $this->doctrineUtils->createSchema($classMetadataArray);

        $this->initEntityTypes();
        $this->updateEntityTypesTranslation();

        $output->writeln(sprintf('<info>Done!</info>'));
        return 0;
    }

    /**
     * 需要初始化Schema的类
     * @return array
     */
    private function getMappedClasses(): array
    {
        return [
            Types::class,
            Translation::class,
            FieldConfiguration::class,
        ];
    }

    private function initEntityTypes(){

        $articleType = new Types();
        $articleType->setBundle('types');
        $articleType->setLabel('Article');
        $articleType->setAlias('article');
        $articleType->setDescription('Use articles to post content about time, such as news, news or logs.');
        $articleType->setTranslatableLocale('en_US');

        $pageType = new Types();
        $pageType->setBundle('types');
        $pageType->setLabel('Page');
        $pageType->setAlias('page');
        $pageType->setDescription('Use basic pages for your static content, such as the "About Us" page.');
        $pageType->setTranslatableLocale('en_US');

        $this->em->persist($articleType);
        $this->em->persist($pageType);

        $this->em->flush();
    }

    private function updateEntityTypesTranslation()
    {
        $typesRepo = $this->em->getRepository(Types::class);

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
    }
}