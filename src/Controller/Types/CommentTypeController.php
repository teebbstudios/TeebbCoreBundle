<?php


namespace Teebb\CoreBundle\Controller\Types;


use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;
use Teebb\CoreBundle\Form\Type\Content\CommentBatchOptionsType;

/**
 * 评论类型Controller
 */
class CommentTypeController extends AbstractEntityTypeController
{
    /**
     * 管理评论
     * @param Request $request
     * @return Response
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function indexCommentsAction(Request $request)
    {
        $commentTypeAlias = $request->get('typeAlias');

        $this->checkActionPermission($request, $commentTypeAlias);

        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('c')->from(Comment::class, 'c')
            ->where('c.commentType = :commentTypeAlias')
            ->setParameter('commentTypeAlias', $commentTypeAlias)
            ->orderBy('c.updatedAt', 'DESC');
        /**
         * @var Pagerfanta $paginator
         */
        $paginator = new Pagerfanta(new DoctrineORMAdapter($queryBuilder, false, false));
        $paginator->setMaxPerPage($limit);
        $paginator->setCurrentPage($page);

        $batchActionForm = $this->createForm(CommentBatchOptionsType::class);
        $batchActionForm->handleRequest($request);

        if ($batchActionForm->isSubmitted() && $batchActionForm->isValid()) {
            $data = $batchActionForm->getData();
            $commentIds = $request->get('comment');

            /**@var Comment[] $comments * */
            $comments = $this->entityManager->createQueryBuilder()->select('c')->from(Comment::class, 'c')
                ->andWhere($queryBuilder->expr()->in('c.id', ':contentIds'))
                ->setParameter('contentIds', $commentIds)->getQuery()->getResult();

            //批量操作
            switch ($data['batch']) {
                case 'batch_delete':
                    foreach ($comments as $comment) {
                        $this->deleteSubstance($this->entityManager, $this->fieldConfigurationRepository, $this->container,
                            'comment', $comment->getCommentType(), $comment);
                    }
                    break;
                case 'batch_rejected':
                    foreach ($comments as $comment) {
                        $comment->setCommentStatus(3);
                        $this->entityManager->persist($comment);
                    }
                    break;
                case 'batch_passed':
                    foreach ($comments as $comment) {
                        $comment->setCommentStatus(2);
                        $this->entityManager->persist($comment);
                    }
                    break;
            }
            $this->entityManager->flush();

            $batchAction = $this->container->get('translator')->trans($data['batch']);

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.comment.batch_action_success', ['%action%' => $batchAction]
            ));

        }

        return $this->render($this->templateRegistry->getTemplate('index_comments', 'comment'), [
            'paginator' => $paginator,
            'action' => 'index_comments',
            'entity_type' => $this->entityTypeService,
            'batch_action_form' => $batchActionForm->createView()
        ]);
    }

    /**
     * 管理评论状态
     * @param Request $request
     * @param Comment $comment
     * @return Response
     */
    public function updateCommentStatusAction(Request $request, Comment $comment)
    {
        $status = $request->get('status');
        $redirectBackURI = $request->get('redirectBackURI');

        if ($status == 'rejected') {
            $comment->setCommentStatus(3);
        }
        if ($status == 'passed') {
            $comment->setCommentStatus(2);
        }

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        $transStatus = $this->container->get('translator')->trans($status);
        $this->addFlash('success', $this->container->get('translator')->trans(
            'teebb.core.comment.update_status', ['%value%' => $comment->getSubject(), '%status%' => $transStatus]
        ));

        return $this->redirect($request->getSchemeAndHttpHost() . $redirectBackURI);
    }


}