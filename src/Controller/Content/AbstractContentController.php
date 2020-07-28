<?php


namespace Teebb\CoreBundle\Controller\Content;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\AbstractService\EntityTypeInterface;
use Teebb\CoreBundle\Controller\SubstanceDBALOptionsTrait;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\FormContractorInterface;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;
use Teebb\CoreBundle\Repository\Types\EntityTypeRepository;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;


/**
 * CRUD controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
abstract class AbstractContentController extends AbstractController
{
    use SubstanceDBALOptionsTrait;

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
    protected $templateRegistry;

    /**
     * @var FormContractorInterface
     */
    protected $formContractor;

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry,
                                FormContractorInterface $formContractor)
    {
        $this->entityManager = $entityManager;
        $this->fieldConfigRepository = $entityManager->getRepository(FieldConfiguration::class);
        $this->typesRepository = $entityManager->getRepository(Types::class);
        $this->templateRegistry = $templateRegistry;
        $this->formContractor = $formContractor;
    }

    /**
     * 用户引用内容、分类字段autocomplete
     */
    public function getSubstancesApi(Request $request)
    {
        $entityClass = $request->get('entity_class');
        $queryLabel = $request->get('query_label');
        $query = $request->get('query');

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c')->from($entityClass, 'c')
            ->where($qb->expr()->like('c.' . $queryLabel, ':query'))
            ->setParameter('query', '%' . $query . '%');

        $substances = $qb->getQuery()->getResult();

        return $this->json($substances, 200, [], ['groups' => ['main']]);
    }

    /**
     * 获取EntityType Service
     *
     * @param Request $request
     * @return EntityTypeInterface
     */
    protected function getEntityTypeService(Request $request): EntityTypeInterface
    {
        $entityTypeServiceId = $request->get('entity_type_service');
        if (null == $entityTypeServiceId) {
            throw new \RuntimeException(sprintf('The route "%s" config must define "entity_type_service" key.', $request->attributes->get('_route')));
        }

        return $this->container->get($entityTypeServiceId);
    }

}