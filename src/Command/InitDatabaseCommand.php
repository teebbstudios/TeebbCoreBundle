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
use Teebb\CoreBundle\Entity\Comment;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\FileManaged;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Entity\TextFormat\Formatter;
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

        $this->initTextFormatter();
        $this->updateFormatterTranslation();

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
            FileManaged::class,
            Content::class,
            Taxonomy::class,
            Comment::class,
            Formatter::class
        ];
    }

    private function initEntityTypes()
    {

        $articleType = new Types();
        $articleType->setBundle('content');
        $articleType->setLabel('Article');
        $articleType->setTypeAlias('article');
        $articleType->setDescription('Use articles to post content about time, such as news, news or logs.');
        $articleType->setTranslatableLocale('en_US');

        $pageType = new Types();
        $pageType->setBundle('content');
        $pageType->setLabel('Page');
        $pageType->setTypeAlias('page');
        $pageType->setDescription('Use basic pages for your static content, such as the "About Us" page.');
        $pageType->setTranslatableLocale('en_US');

        $tags = new Types();
        $tags->setBundle('taxonomy');
        $tags->setLabel('Basic tags');
        $tags->setTypeAlias('tags');
        $tags->setDescription('Basic tags.');
        $tags->setTranslatableLocale('en_US');

        $this->em->persist($articleType);
        $this->em->persist($pageType);
        $this->em->persist($tags);

        $this->em->flush();
    }

    private function updateEntityTypesTranslation()
    {
        $typesRepo = $this->em->getRepository(Types::class);

        $articleType = $typesRepo->findOneBy(['typeAlias' => 'article']);
        $articleType->setLabel('文章');
        $articleType->setDescription('使用文章发布有关时间的内容，如消息，新闻或日志。');
        $articleType->setTranslatableLocale('zh_CN');

        $pageType = $typesRepo->findOneBy(['typeAlias' => 'page']);
        $pageType->setLabel('基本页面');
        $pageType->setDescription('对您的静态内容使用基本页面，比如“关于我们”页面。');
        $pageType->setTranslatableLocale('zh_CN');

        $tags = $typesRepo->findOneBy(['typeAlias' => 'tags']);
        $tags->setLabel('基本分类');
        $tags->setDescription('基本的分类');
        $tags->setTranslatableLocale('zh_CN');

        $this->em->persist($articleType);
        $this->em->persist($pageType);
        $this->em->persist($tags);

        $this->em->flush();
    }

    private function initTextFormatter()
    {
        $fullFormatter = new Formatter();
        $fullFormatter->setName('完整的HTML');
        $fullFormatter->setAlias('full_html');
        $fullFormatter->setFilterSettings([]);
        $fullFormatter->setTranslatableLocale('zh_CN');

        $standardFormatter = new Formatter();
        $standardFormatter->setName('基本的HTML');
        $standardFormatter->setAlias('standard_html');
        $standardFormatter->setFilterSettings([
            'strip_tags' => [
                'filter_name' => true,
                'filter_extra' => '<a> <em> <strong> <cite> <blockquote> <code> <ul> <ol> <li> <dl> <dt> <dd> <h2> <h3> <h4> <h5> <h6> <p> <br> <span> <img>'
            ]
        ]);
        $standardFormatter->setTranslatableLocale('zh_CN');

        $restrictFormatter = new Formatter();
        $restrictFormatter->setName('严格的HTML');
        $restrictFormatter->setAlias('restricted_html');
        $restrictFormatter->setFilterSettings([
            'strip_tags' => [
                'filter_name' => true,
                'filter_extra' => '<a> <em> <strong> <cite> <blockquote> <code> <ul> <ol> <li> <dl> <dt> <dd> <h2> <h3> <h4> <h5> <h6>'
            ]
        ]);
        $restrictFormatter->setTranslatableLocale('zh_CN');

        $this->em->persist($fullFormatter);
        $this->em->persist($standardFormatter);
        $this->em->persist($restrictFormatter);

        $this->em->flush();
    }

    private function updateFormatterTranslation()
    {
        $formatterRepo = $this->em->getRepository(Formatter::class);

        /**@var Formatter $fullFormatter**/
        $fullFormatter = $formatterRepo->findOneBy(['alias'=> 'full_html']);
        $fullFormatter->setName('Full Html');
        $fullFormatter->setTranslatableLocale('en_US');

        /**@var Formatter $standardFormatter**/
        $standardFormatter = $formatterRepo->findOneBy(['alias'=> 'standard_html']);
        $standardFormatter->setName('Standard Html');
        $standardFormatter->setTranslatableLocale('en_US');

        /**@var Formatter $restrictFormatter**/
        $restrictFormatter = $formatterRepo->findOneBy(['alias'=> 'restricted_html']);
        $restrictFormatter->setName('Restrict Html');
        $restrictFormatter->setTranslatableLocale('en_US');

        $this->em->persist($fullFormatter);
        $this->em->persist($standardFormatter);
        $this->em->persist($restrictFormatter);

        $this->em->flush();
    }
}