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
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Teebb\CoreBundle\Doctrine\Utils\DoctrineUtils;
use Teebb\CoreBundle\Entity\Comment;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceImageItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\Configuration\TextFormatItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\Configuration\TextFormatSummaryItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\FileManaged;
use Teebb\CoreBundle\Entity\Group;
use Teebb\CoreBundle\Entity\Menu;
use Teebb\CoreBundle\Entity\MenuItem;
use Teebb\CoreBundle\Entity\Option;
use Teebb\CoreBundle\Entity\Options\System;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Entity\Formatter;
use Teebb\CoreBundle\Entity\Token;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Entity\User;
use Teebb\CoreBundle\Event\SchemaEvent;

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
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(DoctrineUtils $doctrineUtils, EventDispatcherInterface $dispatcher, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->doctrineUtils = $doctrineUtils;
        $this->em = $this->doctrineUtils->getEntityManager();

        parent::__construct();

        $this->dispatcher = $dispatcher;
        $this->passwordEncoder = $passwordEncoder;
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
     * @throws \Exception
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
        $this->iniTypeFields();

        $this->initAdminUser();

        $this->initTextFormatter();
        $this->updateFormatterTranslation();

        $this->initSystemOption();

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
            Formatter::class,
            User::class,
            Token::class,
            Group::class,
            Menu::class,
            MenuItem::class,
            Option::class,
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

        $comment = new Types();
        $comment->setBundle('comment');
        $comment->setLabel('Basic comment');
        $comment->setTypeAlias('comment');
        $comment->setDescription('Basic Comment.');
        $comment->setTranslatableLocale('en_US');

        $userType = new Types();
        $userType->setBundle('user');
        $userType->setLabel('Basic User');
        $userType->setTypeAlias('people');
        $userType->setDescription('Basic User.');
        $userType->setTranslatableLocale('en_US');

        $this->em->persist($articleType);
        $this->em->persist($pageType);
        $this->em->persist($tags);
        $this->em->persist($comment);
        $this->em->persist($userType);

        $this->em->flush();
    }

    /**
     * @throws \Exception
     */
    private function iniTypeFields()
    {
        $this->em->beginTransaction();

        try {
            //article body
            $articleBody = new FieldConfiguration();
            $articleBody->setBundle('content');
            $articleBody->setFieldAlias('article_body');
            $articleBody->setFieldType('textFormatSummary');
            $articleBody->setFieldLabel('正文');
            $articleBody->setTypeAlias('article');
            $articleBody->setSettings(new TextFormatSummaryItemConfiguration());
            //添加完字段在动态添加数据库表
            $event = new SchemaEvent($articleBody);
            $event->setContentEntity(Content::class);
            $this->dispatcher->dispatch($event, SchemaEvent::CREATE_SCHEMA);

            //page body
            $pageBody = new FieldConfiguration();
            $pageBody->setBundle('content');
            $pageBody->setFieldAlias('page_body');
            $pageBody->setFieldType('textFormatSummary');
            $pageBody->setFieldLabel('正文');
            $pageBody->setTypeAlias('page');
            $pageBody->setSettings(new TextFormatSummaryItemConfiguration());
            //添加完字段在动态添加数据库表
            $event = new SchemaEvent($pageBody);
            $event->setContentEntity(Content::class);
            $this->dispatcher->dispatch($event, SchemaEvent::CREATE_SCHEMA);

            //comment body
            $commentBody = new FieldConfiguration();
            $commentBody->setBundle('comment');
            $commentBody->setFieldAlias('comment_body');
            $commentBody->setFieldType('text');
            $commentBody->setFieldLabel('评论');
            $commentBody->setTypeAlias('comment');
            $commentBody->setSettings(new TextFormatItemConfiguration());
            //添加完字段在动态添加数据库表
            $event = new SchemaEvent($commentBody);
            $event->setContentEntity(Taxonomy::class);
            $this->dispatcher->dispatch($event, SchemaEvent::CREATE_SCHEMA);

            //用户添加 头像字段
            $avatarField = new FieldConfiguration();
            $avatarField->setBundle('user');
            $avatarField->setFieldAlias('avatar');
            $avatarField->setFieldType('referenceImage');
            $avatarField->setFieldLabel('头像');
            $avatarField->setTypeAlias('people');
            $avatarField->setSettings(new ReferenceImageItemConfiguration());
            //添加完字段在动态添加数据库表
            $event = new SchemaEvent($avatarField);
            $event->setContentEntity(User::class);
            $this->dispatcher->dispatch($event, SchemaEvent::CREATE_SCHEMA);

            $this->em->persist($articleBody);
            $this->em->persist($pageBody);
            $this->em->persist($commentBody);
            $this->em->persist($avatarField);
            $this->em->flush();

            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
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

        $comment = $typesRepo->findOneBy(['typeAlias' => 'comment']);
        $comment->setLabel('默认评论');
        $comment->setDescription('默认的评论');
        $comment->setTranslatableLocale('zh_CN');

        $userType = $typesRepo->findOneBy(['typeAlias' => 'people']);
        $userType->setLabel('用户类型');
        $userType->setDescription('默认的用户类型');
        $userType->setTranslatableLocale('zh_CN');

        $this->em->persist($articleType);
        $this->em->persist($pageType);
        $this->em->persist($tags);
        $this->em->persist($comment);
        $this->em->persist($userType);

        $this->em->flush();
    }

    private function initTextFormatter()
    {
        $groupRepo = $this->em->getRepository(Group::class);

        $fullFormatter = new Formatter();
        $fullFormatter->setName('完整的HTML');
        $fullFormatter->setAlias('full_html');
        $fullFormatter->setFilterSettings([]);
        $fullFormatter->setTranslatableLocale('zh_CN');
        $fullFormatter->setCkEditorConfig('full');
        $fullFormatter->addGroup($groupRepo->findOneBy(['groupAlias'=>'super_admin']));

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
        $standardFormatter->setCkEditorConfig('standard');
        $standardFormatter->addGroup($groupRepo->findOneBy(['groupAlias'=>'super_admin']));

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
        $restrictFormatter->setCkEditorConfig('basic');
        $restrictFormatter->addGroup($groupRepo->findOneBy(['groupAlias'=>'super_admin']));
        $restrictFormatter->addGroup($groupRepo->findOneBy(['groupAlias'=>'user']));

        $this->em->persist($fullFormatter);
        $this->em->persist($standardFormatter);
        $this->em->persist($restrictFormatter);

        $this->em->flush();
    }

    private function updateFormatterTranslation()
    {
        $formatterRepo = $this->em->getRepository(Formatter::class);

        /**@var Formatter $fullFormatter * */
        $fullFormatter = $formatterRepo->findOneBy(['alias' => 'full_html']);
        $fullFormatter->setName('Full Html');
        $fullFormatter->setTranslatableLocale('en_US');


        /**@var Formatter $standardFormatter * */
        $standardFormatter = $formatterRepo->findOneBy(['alias' => 'standard_html']);
        $standardFormatter->setName('Standard Html');
        $standardFormatter->setTranslatableLocale('en_US');

        /**@var Formatter $restrictFormatter * */
        $restrictFormatter = $formatterRepo->findOneBy(['alias' => 'restricted_html']);
        $restrictFormatter->setName('Restrict Html');
        $restrictFormatter->setTranslatableLocale('en_US');

        $this->em->persist($fullFormatter);
        $this->em->persist($standardFormatter);
        $this->em->persist($restrictFormatter);

        $this->em->flush();
    }

    private function initAdminUser()
    {
        $administratorGroup = new Group();
        $administratorGroup->setName('超级管理员');
        $administratorGroup->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN']);
        $administratorGroup->setGroupAlias('super_admin');
        $administratorGroup->setCkeditorConfig('full');

        $registerUserGroup = new Group();
        $registerUserGroup->setName('注册用户');
        $registerUserGroup->setGroupAlias('user');
        $registerUserGroup->setCkeditorConfig('standard');
        $registerUserGroup->setPermissions([
            'permission' => [
                'file_upload'
            ]
        ]);

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setUsernameCanonical('admin');
        $admin->setEmail('admin@example.com');
        $admin->setEmailCanonical('admin@example.com');
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, 'admin'));
        $admin->setSalt(null);
        $admin->setEnabled(true);
        $admin->addGroup($administratorGroup);
        foreach ($administratorGroup->getRoles() as $role)
        {
            $admin->addRole($role);
        }

        $this->em->persist($administratorGroup);
        $this->em->persist($registerUserGroup);
        $this->em->persist($admin);
        $this->em->flush();
    }

    private function initSystemOption()
    {
        $system = new System();
        $system->setSiteName('Teebb');
        $system->setSiteEmail('admin@admin.com');

        $option = new Option();
        $option->setOptionName('system');
        $option->setOptionValue($system);

        $this->em->persist($option);

        $this->em->flush();
    }
}