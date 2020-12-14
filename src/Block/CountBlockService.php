<?php


namespace Teebb\CoreBundle\Block;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Types\Types;
use Twig\Environment;

/**
 * 计算某段时间内新增的内容数量
 */
class CountBlockService extends AbstractBlockService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(Environment $templating, EntityManagerInterface $entityManager)
    {
        parent::__construct($templating);

        $this->entityManager = $entityManager;

    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entity_class' => Content::class,
            'bundle' => 'content',
            'label' => '',
            'translation_domain' => 'messages',
            'icon' => 'fas fa-file-alt',
            'border' => 'border-left-primary',
            'property' => 'createdAt',
            'duration' => '-1 month',
            'template' => '@TeebbCore/blocks/count_block.html.twig',
        ]);
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        // merge settings
        $settings = $blockContext->getSettings();

        $startTime = strtotime($settings['duration']);

        if ($startTime === false) {
            throw new \RuntimeException(sprintf('The block config duration \'%s\' is not an php date strtotime string.', $settings['duration']));
        }

        //查询所有具有起始时间变量的entity数量
        $DQL = 'SELECT COUNT(o) FROM ' . $settings['entity_class'] . ' o WHERE o. ' . $settings['property'] . ' >= :startTime';

        //查询不同内容类型的数量
        if ($settings['entity_class'] == Types::class) {
            $DQL = 'SELECT COUNT(o) FROM ' . $settings['entity_class'] . ' o WHERE o.bundle = :bundle';
        }
        /**@var Query $q * */
        $q = $this->entityManager->createQuery($DQL);

        if ($settings['entity_class'] == Types::class) {
            $q->setParameter('bundle', $settings['bundle']);
        } else {
            $q->setParameter('startTime', $startTime);
        }

        $number = $q->getResult(Query::HYDRATE_SINGLE_SCALAR);;

        return $this->renderResponse($blockContext->getTemplate(), [
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
            'count' => $number,
        ], $response);
    }


}