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
 * 搜索内容block
 * @package Teebb\CoreBundle\Block
 */
class SearchContentsBlockService extends AbstractBlockService
{
    public function __construct(Environment $templating)
    {
        parent::__construct($templating);
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => '',
            'translation_domain' => 'messages',
            'template' => '@TeebbCore/blocks/search_form.html.twig', //搜索框样式
            'route' => 'teebb_content_index', //搜索条件添加到此页面Route，在此页面显示结果
            'form_class' => '', //search表单样式类
            'property' => 'title', //要搜索的EntityType对应Entity的属性
            'extra' => [
                'fields' => [],  //额外要搜索的字段中的内容，此处为字段别名数组
            ]
        ]);
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        // merge settings
        $settings = $blockContext->getSettings();

        return $this->renderResponse($blockContext->getTemplate(), [
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
        ], $response);
    }

}