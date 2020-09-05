<?php


namespace Teebb\CoreBundle\Controller\Types;

use Symfony\Component\Form\Extension\Core\Type\FormType;
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
        $this->checkActionPermission($request);

        $typeAlias = $request->get('typeAlias');

        $this->checkTypeObjectExist($typeAlias);

        $taxonomyRepo = $this->entityManager->getRepository(Taxonomy::class);

        $rootTaxonomies = $taxonomyRepo->findBy(['taxonomyType' => $typeAlias, 'parent' => null]);

        return $this->render($this->templateRegistry->getTemplate('index_term', 'terms'), [
            'action' => 'index_term',
            'taxonomies' => $rootTaxonomies,
            'entity_type' => $this->entityTypeService,
            'taxonomyRepo' => $taxonomyRepo
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
        $this->checkActionPermission($request);

        $typeAlias = $request->get('typeAlias');
        $bundle = $this->entityTypeService->getBundle();

        $this->checkTypeObjectExist($typeAlias);

        $data_class = $this->entityTypeService->getEntityClassName();
        $entityFormType = $this->entityTypeService->getEntityFormType();

        $form = $this->createForm($entityFormType,
            null,
            ['bundle' => $bundle, 'type_alias' => $typeAlias, 'data_class' => $data_class]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                //持久化内容和字段
                /**@var Taxonomy $taxonomy * */
                $taxonomy = $this->persistSubstance($this->entityManager, $this->fieldConfigurationRepository,
                    $form, $bundle, $typeAlias, $data_class);

                $this->addFlash('success', $this->container->get('translator')->trans(
                    'teebb.core.taxonomy.create_success', ['%value%' => $taxonomy->getTerm()]
                ));

                //词汇添加完成，跳转到列表页
                return $this->redirectToRoute('taxonomy_index_term', ['typeAlias' => $typeAlias]);

            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

        }
        return $this->render($this->templateRegistry->getTemplate('create_term', 'terms'), [
            'action' => 'create_term',
            'form' => $form->createView(),
            'entity_type' => $this->entityTypeService,
            'type_alias' => $typeAlias,
            'extra_assets' => ['autocompletejs'], //当前页面需要额外添加的assets库
        ]);
    }


    /**
     * 更新分类词汇
     *
     * @param Request $request
     * @param Taxonomy $taxonomy
     * @return Response
     */
    public function updateTermAction(Request $request, Taxonomy $taxonomy)
    {
        $this->checkActionPermission($request);

        $typeAlias = $request->get('typeAlias');
        $bundle = $this->entityTypeService->getBundle();

        $this->checkTypeObjectExist($typeAlias);
        $data_class = $this->entityTypeService->getEntityClassName();
        $entityFormType = $this->entityTypeService->getEntityFormType();

        $form = $this->createForm($entityFormType,
            $taxonomy,
            ['bundle' => $bundle, 'type_alias' => $typeAlias, 'data_class' => $data_class]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                //持久化内容和字段
                /**@var Taxonomy $taxonomy * */
                $taxonomy = $this->persistSubstance($this->entityManager, $this->fieldConfigurationRepository,
                    $form, $bundle, $typeAlias, $data_class);

                $this->addFlash('success', $this->container->get('translator')->trans(
                    'teebb.core.taxonomy.update_success', ['%value%' => $taxonomy->getTerm()]
                ));

                //词汇更新完成，跳转到列表页
                return $this->redirectToRoute('taxonomy_index_term', ['typeAlias' => $typeAlias]);

            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

        }
        return $this->render($this->templateRegistry->getTemplate('update_term', 'terms'), [
            'action' => 'update_term',
            'form' => $form->createView(),
            'entity_type' => $this->entityTypeService,
            'subject' => $taxonomy,
            'type_alias' => $typeAlias,
            'extra_assets' => ['autocompletejs'], //当前页面需要额外添加的assets库
        ]);
    }


    /**
     * 删除分类词汇
     *
     * @param Request $request
     * @param Taxonomy $taxonomy
     * @return Response
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function deleteTermAction(Request $request, Taxonomy $taxonomy)
    {
        $this->checkActionPermission($request);

        $typeAlias = $request->get('typeAlias');
        $bundle = $this->entityTypeService->getBundle();

        $this->checkTypeObjectExist($typeAlias);

        $deleteForm = $this->formContractor->generateDeleteForm($request->attributes->get('_route'), FormType::class, $taxonomy);

        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            if ($deleteForm->get('_method')->getData() === 'DELETE') {

                $conn = $this->entityManager->getConnection();

                $conn->beginTransaction();
                try {

                    $this->deleteSubstance($this->entityManager, $this->fieldConfigurationRepository, $this->container,
                        $bundle, $typeAlias, $taxonomy);

                    $this->addFlash('success', $this->container->get('translator')->trans(
                        'teebb.core.content.delete_success', ['%value%' => $taxonomy->getTerm()]
                    ));

                    $conn->commit();
                    //词汇删除完成，跳转到列表页
                    return $this->redirectToRoute('taxonomy_index_term', ['typeAlias' => $typeAlias]);

                } catch (\Exception $e) {
                    $conn->rollBack();
                    $this->addFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render($this->templateRegistry->getTemplate('delete_term', 'terms'), [
            'action' => 'delete_term',
            'delete_form' => $deleteForm->createView(),
            'entity_type' => $this->entityTypeService,
            'subject' => $taxonomy,
            'type_alias' => $typeAlias
        ]);
    }


}