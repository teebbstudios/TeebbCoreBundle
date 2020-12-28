<?php


namespace Teebb\CoreBundle\Controller\Content;


use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Taxonomy;

/**
 * 分类entity controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class TaxonomyController extends AbstractContentController
{
    /**
     * 根据关键字搜索对应分类词汇
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchTaxonomyAjaxAction(Request $request)
    {
        $this->isGranted('ROLE_ADMIN');

        $keyword = $request->get('keyword');

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('t')->from(Taxonomy::class, 't')
            ->where($qb->expr()->like('t.term', ':keyword'))
            ->setParameter('keyword', '%' . $keyword . '%');

        $substances = $qb->getQuery()->getResult();

        return $this->json($substances, 200, [], ['groups' => ['main']]);
    }
}