<?php


namespace Teebb\CoreBundle\Block;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Content;
use Twig\Environment;
use function Doctrine\ORM\QueryBuilder;

/**
 * 各种内容列表Block
 * @package Teebb\CoreBundle\Block
 */
class ContentsBlockService extends AbstractBlockService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(Environment $templating, EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        parent::__construct($templating);

        $this->entityManager = $entityManager;

        $this->container = $container;
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entity_class' => Content::class,
            'label' => '',
            'translation_domain' => 'messages',
            'limit' => 5,
            'template' => '@TeebbCore/blocks/last_contents.html.twig',
            'criteria' => [],
            'order' => [],
            'exclude' => [],
        ]);
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        // merge settings
        $settings = $blockContext->getSettings();
        /**@var EntityRepository $contentsRepository * */
        $contentsRepository = $this->entityManager->getRepository($settings['entity_class']);

        $qb = $contentsRepository->createQueryBuilder('c');

        foreach ($settings['criteria'] as $key => $value) {
            $qb->andWhere($qb->expr()->eq('c.' . $key, $value));
        }

        //排除条件
        if (!empty($settings['exclude'])) {
            foreach ($settings['exclude'] as $key => $value) {
                $qb->andWhere($qb->expr()->neq('c.' . $key, $value));
            }
        }

        foreach ($settings['order'] as $key => $value) {
            $qb->addOrderBy('c.' . $key, $value);
        }

        $qb->setMaxResults($settings['limit']);

        $contents = $qb->getQuery()->getResult();

        return $this->renderResponse($blockContext->getTemplate(), [
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
            'contents' => $contents,
            'container' => $this->container
        ], $response);
    }

}