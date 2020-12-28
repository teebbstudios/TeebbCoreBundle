<?php


namespace Teebb\CoreBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Voter\Field\FieldVoter;

class FieldController extends AbstractController
{
    /**
     * 当字段数量不限制时，使用此ajax可动态删除不需要的字段行
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function removeFieldItemAction(Request $request,
                                          EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted(FieldVoter::FIELD_ITEM_DELETE);

        $bundle = $request->get('bundle');
        $fieldAlias = $request->get('field-alias');
        $fieldItemId = $request->get('field-item-id');
        $fieldTableName = $bundle . '__field_' . $fieldAlias;

        $connection = $entityManager->getConnection();
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->delete($fieldTableName)
            ->andWhere(
                $queryBuilder->expr()->eq('id', ':id')
            )
            ->setParameter('id', $fieldItemId)->execute();

        return $this->json([], 204);
    }

}