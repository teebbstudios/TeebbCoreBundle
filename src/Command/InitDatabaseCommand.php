<?php


namespace Teebb\CoreBundle\Command;

use Gedmo\Translatable\Entity\Translation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Teebb\CoreBundle\Doctrine\Utils\DoctrineUtils;
use Teebb\CoreBundle\Entity\Types\CommentType;
use Teebb\CoreBundle\Entity\Types\ContentType;
use Teebb\CoreBundle\Entity\Types\TaxonomyType;

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

    public function __construct(DoctrineUtils $doctrineUtils)
    {
        $this->doctrineUtils = $doctrineUtils;

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
            ContentType::class,
            TaxonomyType::class,
            CommentType::class,
            Translation::class
        ];
    }
}