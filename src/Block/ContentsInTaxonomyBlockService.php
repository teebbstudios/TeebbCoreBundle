<?php


namespace Teebb\CoreBundle\Block;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceTaxonomyItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Repository\BaseContentRepository;
use Twig\Environment;

/**
 * 获取某分类词汇下所有内容
 */
class ContentsInTaxonomyBlockService extends AbstractBlockService
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
            'label' => '',
            'get_children' => false, #是否获取分类词汇子节点的内容
            'limit' => null,
            'translation_domain' => 'messages',
            'order' => ['id' => 'DESC'],
            'template' => '@TeebbCore/blocks/last_contents.html.twig',
        ]);

        $resolver->setDefined('taxonomy_slug');
        $resolver->setRequired('taxonomy_slug');

        #bundle需与entity_class对应，用于查询不同bundle的内容
        $resolver->setDefined('bundle');
        $resolver->setRequired('bundle');
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null): Response
    {
        // merge settings
        $settings = $blockContext->getSettings();

        $taxonomyRepo = $this->entityManager->getRepository(Taxonomy::class);
        $currentTaxonomy = $taxonomyRepo->findOneBy(['slug' => $settings['taxonomy_slug']]);

        $conditionTaxonomyIds[] = $currentTaxonomy->getId();

        //如果需要获取分类词汇子节点的内容
        if ($settings['get_children']) {
            $childTaxonomies = $taxonomyRepo->children($currentTaxonomy);
            /**@var Taxonomy $childTaxonomy * */
            foreach ($childTaxonomies as $childTaxonomy) {
                $conditionTaxonomyIds[] = $childTaxonomy->getId();
            }
        }

        //所有使用当前taxonomy的id 数组做为$criteria
        //查询所有引用分类类型的字段
        $fieldsRepo = $this->entityManager->getRepository(FieldConfiguration::class);
        $fields = $fieldsRepo->findBy(['fieldType' => 'referenceTaxonomy', 'bundle' => $settings['bundle']]);

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
            if (in_array($currentTaxonomy->getTaxonomyType(), $taxonomyTypeAliasArray)) {
                $fieldTableName = $settings['bundle'].'__field_' . $fieldConfiguration->getFieldAlias();

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

        /**@var BaseContentRepository $baseContentRepository * */
        $baseContentRepository = $this->entityManager->getRepository($settings['entity_class']);

        $contents = $baseContentRepository->findBy($criteria, $settings['order'], $settings['limit']);

        return $this->renderResponse($blockContext->getTemplate(), [
            'block' => $blockContext->getBlock(),
            'settings' => $settings,
            'contents' => $contents,
            'count' => sizeof($contents),
        ], $response);
    }


}