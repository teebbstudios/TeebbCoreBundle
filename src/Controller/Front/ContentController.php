<?php


namespace Teebb\CoreBundle\Controller\Front;


use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceTaxonomyItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Repository\BaseContentRepository;
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
        $entityTypeService = $this->container->get('teebb.core.entity_type.content');

        $data = $entityTypeService->getAllFieldsData($content, $content->getTypeAlias());

        return $this->render($this->templateRegistry->getTemplate('content_show', 'front'), [
            'entity_type' => $entityTypeService,
            'subject' => $content,
            'type_alias' => $content->getTypeAlias(),
            'field_data' => $data,
        ]);
    }



    /**
     * 显示分类词下所有内容
     * @param Request $request
     * @param Taxonomy $taxonomy
     * @return Response
     */
    public function listTaxonomyContents(Request $request, Taxonomy $taxonomy): Response
    {
        $entityTypeService = $this->container->get('teebb.core.entity_type.content');

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $criteria = [];

        $taxonomyRepo = $this->entityManager->getRepository(Taxonomy::class);
        $conditionTaxonomyIds[] = $taxonomy->getId();
        //如果taxonomy有子taxonomy则把子taxonomy id也并入计算条件
        $childTaxonomies = $taxonomyRepo->children($taxonomy);
        /**@var Taxonomy $childTaxonomy * */
        foreach ($childTaxonomies as $childTaxonomy) {
            $conditionTaxonomyIds[] = $childTaxonomy->getId();
        }

        //所有使用当前taxonomy的id 数组做为$criteria
        //查询所有引用分类类型的字段
        $fieldsRepo = $this->entityManager->getRepository(FieldConfiguration::class);
        $fields = $fieldsRepo->findBy(['fieldType' => 'referenceTaxonomy', 'bundle' => 'content']);

        $contentIdArray = [];
        foreach ($fields as $fieldConfiguration) {
            /**@var ReferenceTaxonomyItemConfiguration $fieldSettings * */
            $fieldSettings = $fieldConfiguration->getSettings();
            if (!$fieldSettings instanceof ReferenceTaxonomyItemConfiguration) {
                continue;
            }
            $taxonomyTypes = $fieldSettings->getReferenceTypes();

            //当前字段所引用的分类字段类型别名数组
            $taxonomyTypeAliasArray = [];
            /**@var Types $taxonomyType * */
            foreach ($taxonomyTypes as $taxonomyType) {
                $taxonomyTypeAliasArray[] = $taxonomyType->getTypeAlias();
            }
            //如果当前taxonomy的类型在字段的分类引用列表中，则获取字段表表名称，并查询所有引用了此分类的content entity_id 存入数组中。
            if (in_array($taxonomy->getTaxonomyType(), $taxonomyTypeAliasArray)) {
                $fieldTableName = 'content__field_' . $fieldConfiguration->getFieldAlias();

                //DBAL查询对应字段表获取content entity_id 存入数组中
                $connection = $this->entityManager->getConnection();
                $queryBuilder = $connection->createQueryBuilder();
                $contentIdResult = $queryBuilder->select('entity_id')->from($fieldTableName)
                    ->andWhere(
                        $queryBuilder->expr()->in('reference_entity_id', $conditionTaxonomyIds)
                    )
                    ->execute()->fetchAll();

                foreach ($contentIdResult as $columnRow) {
                    $contentIdArray[] = $columnRow['entity_id'];
                }
            }
        }
        //如果$contentIdArray不为空，则查询
        if (empty($contentIdArray)) {
            $criteria['id'] = [null];
        } else {
            $criteria['id'] = $contentIdArray;
        }
        $criteria['status'] = 'publish';

        /**@var BaseContentRepository $baseContentRepository * */
        $baseContentRepository = $this->entityManager->getRepository($entityTypeService->getEntityClassName());

        /**
         * @var Pagerfanta $paginator
         */
        $paginator = $baseContentRepository->createPaginator($criteria, ['boolTop' => 'DESC', 'id' => 'DESC', 'updatedAt' => 'DESC']);
        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        return $this->render($this->templateRegistry->getTemplate('list_taxonomy_contents', 'front'), [
            'entity_type' => $entityTypeService,
            'taxonomy' => $taxonomy,
            'paginator' => $paginator,
        ]);
    }

    /**
     * 显示某内容类型下所有内容
     * @param Request $request
     * @param Types $types
     * @return Response
     */
    public function listTypeContents(Request $request, Types $types): Response
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $entityTypeService = $this->container->get('teebb.core.entity_type.content');

        /**@var BaseContentRepository $baseContentRepository * */
        $baseContentRepository = $this->entityManager->getRepository($entityTypeService->getEntityClassName());

        /**
         * @var Pagerfanta $paginator
         */
        $paginator = $baseContentRepository->createPaginator(['typeAlias' => $types->getTypeAlias(), 'status' => 'publish'],
            ['boolTop' => 'DESC', 'id' => 'DESC', 'updatedAt' => 'DESC']);
        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        return $this->render($this->templateRegistry->getTemplate('list_type_contents', 'front'), [
            'entity_type' => $entityTypeService,
            'paginator' => $paginator,
            'type' => $types
        ]);
    }

}
