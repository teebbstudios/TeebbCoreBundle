<?php


namespace Teebb\CoreBundle\Controller\Front;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Templating\TemplateRegistry;

/**
 * 前台内容页面
 */
class ContentController extends AbstractController
{
    /**
     * @var TemplateRegistry
     */
    private $templateRegistry;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry)
    {
        $this->templateRegistry = $templateRegistry;
        $this->entityManager = $entityManager;
    }

    /**
     * 前台显示内容页面
     * @param Request $request
     * @param Content $content
     * @return Response
     */
    public function showAction(Request $request, Content $content)
    {
        $entityTypeService = $this->container->get('teebb.core.entity_type.content_entity_type');

        $data = $entityTypeService->getAllFieldsData($content, $content->getTypeAlias());

        return $this->render($this->templateRegistry->getTemplate('content_show', 'front'), [
            'action' => 'show',
            'entity_type' => $entityTypeService,
            'subject' => $content,
            'type_alias' => $content->getTypeAlias(),
            'fieldDatas' => $data,
        ]);
    }

//    public function
}