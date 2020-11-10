<?php


namespace Teebb\CoreBundle\Controller\Types;


use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        return $this->redirect($redirectBackURI);
    }


    /**
     * 删除评论
     * @param Request $request
     * @param Comment $comment
     * @return Response
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function deleteCommentItemAction(Request $request, Comment $comment)
    {
        $redirectBackURI = $request->get('redirectBackURI');

        $deleteForm = $this->formContractor->generateDeleteForm('delete_comment_' . $comment->getId(), FormType::class, $comment);
        $deleteForm->add('delete', SubmitType::class, ['label' => 'teebb.core.form.delete_comment', 'label_html' => true, 'attr' => ['class' => 'btn-danger btn-sm btn-icon-split mr-2']]);
        $deleteForm->add('cancel', SubmitType::class, ['label' => 'teebb.core.form.cancel', 'label_html' => true, 'attr' => ['class' => 'btn-secondary btn-sm btn-icon-split mr-2']]);

        $deleteForm->handleRequest($request);

        if ($deleteForm->isSubmitted() && $deleteForm->isValid()) {
            //如果是删除按钮
            if ($deleteForm->get('delete')->isClicked()) {
                if ($deleteForm->get('_method')->getData() === 'DELETE') {
                    $conn = $this->entityManager->getConnection();

                    $conn->beginTransaction();
                    try {
                        $this->deleteSubstance($this->entityManager, $this->fieldConfigurationRepository, $this->container,
                            'comment', $comment->getCommentType(), $comment);

                        $this->addFlash('success', $this->container->get('translator')->trans(
                            'teebb.core.comment.delete_success', ['%value%' => $comment->getSubject()]
                        ));

                        $conn->commit();

                        //评论删除完成，跳转到redirect页
                        return $this->redirect($redirectBackURI);

                    } catch (\Exception $e) {
                        $conn->rollBack();
                        $this->addFlash('danger', $e->getMessage());
                    }

                }
            }
            //如果是取消按钮 跳转到redirect页
            if ($deleteForm->get('cancel')->isClicked()) {
                return $this->redirect($redirectBackURI);
            }
        }

        return $this->render($this->templateRegistry->getTemplate('delete_form', 'comment'), [
            'delete_form' => $deleteForm->createView(),
            'comment' => $comment,
            'action' => 'delete_comment'
        ]);
    }

}