<?php


namespace Teebb\CoreBundle\Controller\Content;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Teebb\CoreBundle\AbstractService\EntityTypeInterface;
use Teebb\CoreBundle\Controller\SubstanceDBALOptionsTrait;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\FormContractorInterface;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;
use Teebb\CoreBundle\Repository\Types\EntityTypeRepository;
use Teebb\CoreBundle\Templating\TemplateRegistry;


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

    /**
     * @var Security
     */
    protected $security;

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry,
                                FormContractorInterface $formContractor, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->fieldConfigRepository = $entityManager->getRepository(FieldConfiguration::class);
        $this->typesRepository = $entityManager->getRepository(Types::class);
        $this->templateRegistry = $templateRegistry;
        $this->formContractor = $formContractor;
        $this->security = $security;
    }

    /**
     * 用户引用内容、分类字段autocomplete
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getSubstancesApi(Request $request)
    {
        $this->security->isGranted('ROLE_USER');

        $entityClass = $request->get('entity_class');
        $queryLabel = $request->get('query_label'); //查询的关键字在内容实体中对应的内容的属性
        $query = $request->get('query');
        $referenceTypes = $request->get('reference_types');
        $referenceTypesArray = explode(',', $referenceTypes);
        $typeLabel = $request->get('type_label'); //引用的类型在内容实体中对应的属性

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c')->from($entityClass, 'c')
            ->where($qb->expr()->like('c.' . $queryLabel, ':query'))
            ->andWhere($qb->expr()->in('c.' . $typeLabel, ':reference_types'))
            ->setParameter('query', '%' . $query . '%')
            ->setParameter('reference_types', $referenceTypesArray);

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