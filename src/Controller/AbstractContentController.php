<?php


namespace Teebb\CoreBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\ContentType;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;
use Teebb\CoreBundle\Repository\Types\EntityTypeRepository;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;


/**
 * 内容Content CRUD controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class AbstractContentController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var FieldConfigurationRepository
     */
    protected $fieldConfigRepository;

    /**
     * @var EntityTypeRepository
     */
    protected $typesRepository;
    /**
     * @var TemplateRegistry
     */
    private $templateRegistry;

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry)
    {
        $this->entityManager = $entityManager;

        $this->fieldConfigRepository = $entityManager->getRepository(FieldConfiguration::class);
        $this->typesRepository = $entityManager->getRepository(Types::class);
        $this->templateRegistry = $templateRegistry;
    }

    /**
     * 创建内容首页，选择内容类型
     *
     * @param Request $request
     * @return Response
     */
    public function createIndexAction(Request $request)
    {
        $page = $request->get('page', 1);
        /**
         * @var Pagerfanta $paginator
         */
        $paginator = $this->typesRepository->createPaginator(['bundle' => 'types']);
        $paginator->setCurrentPage($page);

        return $this->render($this->templateRegistry->getTemplate('list_types', 'content'), [
            'data' => $paginator->getCurrentPageResults(),
            'action' => 'create_content'
        ]);
    }

    /**
     * 创建内容
     *
     * @param Request $request
     * @param Types $types
     * @return Response
     */
    public function createAction(Request $request, Types $types)
    {
        $form = $this->createForm(ContentType::class, null, ['bundle' => $types->getBundle(), 'type_alias' => $types->getTypeAlias()]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->get('bu_er_zhi')->getData(),$form->get('zheng_wen')->getData(),$form->get('biao_ti')->getData());
        }
        return $this->render($this->templateRegistry->getTemplate('create', 'content'), [
            'action' => 'create_content',
            'form' => $form->createView()
        ]);
    }
}