<?php


namespace Teebb\CoreBundle\Block;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Content;
use Twig\Environment;

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
            'property' => 'createdAt',
            'order' => 'ASC',
            'limit' => 5,
            'type_property' => '', #entity_class类的类型别名属性,例如：Content类的属性：typeAlias
            'type_alias' => null, #类型别名 用于获取某类型的内容
            'template' => '@TeebbCore/blocks/last_contents.html.twig',
        ]);
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        // merge settings
        $settings = $blockContext->getSettings();

        $contentsRepository = $this->entityManager->getRepository($settings['entity_class']);

        $criteria = [];
        if ($settings['type_alias']) {
            $criteria[$settings['type_property']] = $settings['type_alias'];
        }
        $contents = $contentsRepository->findBy($criteria, [$settings['property'] => $settings['order']], $settings['limit']);

        return $this->renderResponse($blockContext->getTemplate(), [
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
            'contents' => $contents,
            'container' => $this->container
        ], $response);
    }

}