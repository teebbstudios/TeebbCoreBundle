<?php


namespace Teebb\CoreBundle\Controller\Types;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Teebb\CoreBundle\Controller\SubstanceDBALOptionsTrait;
use Teebb\CoreBundle\Entity\Taxonomy;

/**
 * 分类类型Controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class TaxonomyTypeController extends AbstractEntityTypeController
{
    use SubstanceDBALOptionsTrait;

    /**
     * 管理分类词汇
     *
     * @param Request $request
     * @return Response
     */
    public function indexTermAction(Request $request)
    {
        $typeAlias = $request->get('typeAlias');

        $this->checkTypeObjectExist($typeAlias);

        $taxonomyRepo = $this->entityManager->getRepository(Taxonomy::class);

        $rootTaxonomies = $taxonomyRepo->findBy(['taxonomyType' => $typeAlias, 'parent' => null]);

        return $this->render($this->templateRegistry->getTemplate('index_term', 'terms'), [
            'action' => 'index_term',
            'taxonomies' => $rootTaxonomies,
            'entity_type' => $this->entityTypeService
        ]);
    }


    /**
     * 添加分类词汇
     *
     * @param Request $request
     * @return Response
     */
    public function createTermAction(Request $request)
    {
        $typeAlias = $request->get('typeAlias');

        $this->checkTypeObjectExist($typeAlias);

        return $this->render($this->templateRegistry->getTemplate('create_term', 'terms'), [

        ]);
    }


    /**
     * 更新分类词汇
     *
     * @param Request $request
     * @return Response
     */
    public function updateTermAction(Request $request, Taxonomy $taxonomy)
    {
        $typeAlias = $request->get('typeAlias');
        $bundle = $this->entityTypeService->getBundle();

        $this->checkTypeObjectExist($typeAlias);

        return $this->render($this->templateRegistry->getTemplate('update_term', 'terms'), [

        ]);
    }


    /**
     * 删除分类词汇
     *
     * @param Request $request
     * @return Response
     */
    public function deleteTermAction(Request $request, Taxonomy $taxonomy)
    {
        $typeAlias = $request->get('typeAlias');
        $bundle = $this->entityTypeService->getBundle();

        $this->checkTypeObjectExist($typeAlias);

        return $this->render($this->templateRegistry->getTemplate('delete_term', 'terms'), [

        ]);
    }


}