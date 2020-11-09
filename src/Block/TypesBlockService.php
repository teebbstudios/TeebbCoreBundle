<?php


namespace Teebb\CoreBundle\Block;


use Doctrine\ORM\EntityManagerInterface;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Types\Types;
use Twig\Environment;


class TypesBlockService extends AbstractBlockService
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
            'label' => 'Welcome',
            'bundle' => 'content',
            'translation_domain' => 'messages',
            'template' => '@TeebbCore/blocks/content_types.html.twig',
        ]);
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        // merge settings
        $settings = $blockContext->getSettings();

        $typesRepo = $this->entityManager->getRepository(Types::class);

        $types = $typesRepo->findBy(['bundle'=>$settings['bundle']]);

        return $this->renderResponse($blockContext->getTemplate(), [
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
            'types' => $types
        ], $response);
    }


}