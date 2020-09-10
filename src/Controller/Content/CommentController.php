<?php


namespace Teebb\CoreBundle\Controller\Content;


use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;
use Teebb\CoreBundle\Form\FormContractorInterface;
use Teebb\CoreBundle\Form\Type\Content\CommentType;

/**
 * 评论entity controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class CommentController extends AbstractContentController
{
    /**
     * 获取当前内容所有评论
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $bundle = $request->get('bundle');
        $typeAlias = $request->get('typeAlias');
        $slug = $request->get('slug');
        $commentType = $request->get('commentType');
        $fieldAlias = $request->get('fieldAlias');
        $redirectBackURI = $request->get('redirectBackURI');

        $commentRepo = $this->entityManager->getRepository(Comment::class);

        $commentTypeService = $this->getEntityTypeService($request);

        $comments = $commentRepo->findBy([
            'bundle' => $bundle,
            'typeAlias' => $typeAlias,
            'thread' => $slug,
            'commentType' => $commentType,
            'parent' => null,
            'commentFieldAlias' => $fieldAlias,
            'commentStatus' => 2  //只有通过审核的评论才会显示
        ]);

        return $this->render($this->templateRegistry->getTemplate('index', 'comment'), [
            'comments' => $comments,
            'commentRepo' => $commentRepo,
            'entity_type' => $commentTypeService,
            'redirectBackURI' => $redirectBackURI
        ]);
    }

    /**
     * 添加新评论
     *
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $bundle = $request->get('bundle');
        $typeAlias = $request->get('typeAlias');
        $slug = $request->get('slug');
        $commentType = $request->get('commentType');
        $fieldAlias = $request->get('fieldAlias');
        $redirectBackURI = $request->get('redirectBackURI');

        /**@var FormContractorInterface $formContractor * */
        $formContractor = $this->container->get('teebb.core.form.contractor');

        $formBuilder = $formContractor->getFormBuilder('comment_' . $fieldAlias, CommentType::class, null, [
            'bundle' => 'comment',
            'type_alias' => $commentType,
            'data_class' => Comment::class
        ]);

        $formBuilder->setAction($request->getRequestUri());

        $commentForm = $formBuilder->getForm();

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            /**@var Comment $comment * */
            $comment = $commentForm->getData();
            $comment->setTypeAlias($typeAlias);
            $comment->setThread($slug);
            $comment->setBundle($bundle);
            $comment->setCommentFieldAlias($fieldAlias);
            $comment->setCommentStatus(1);

            try {
                //持久化评论和字段
                $comment = $this->persistSubstance($this->entityManager, $this->fieldConfigRepository,
                    $commentForm, 'comment', $commentType, Comment::class);

                $this->addFlash('success', $this->container->get('translator')->trans('teebb.core.comment.create_success'));

                //评论完成，跳转到内容页
                return $this->redirect($request->getSchemeAndHttpHost() . $redirectBackURI);

            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

        }

        return $this->render($this->templateRegistry->getTemplate('form', 'comment'), [
            'comment_form' => $commentForm->createView()
        ]);

    }

    /**
     * 回复评论
     * @param Request $request
     * @param Comment $parentComment
     * @return Response
     */
    public function replyComment(Request $request, Comment $parentComment)
    {
        $redirectBackURI = $request->get('redirectBackURI');

        /**@var FormContractorInterface $formContractor * */
        $formContractor = $this->container->get('teebb.core.form.contractor');

        $formBuilder = $formContractor->getFormBuilder('comment_' . $parentComment->getCommentFieldAlias(), CommentType::class, null, [
            'bundle' => 'comment',
            'type_alias' => $parentComment->getCommentType(),
            'data_class' => Comment::class
        ]);
        $commentForm = $formBuilder->getForm();

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid())
        {
            /**@var Comment $comment * */
            $comment = $commentForm->getData();
            $comment->setTypeAlias($parentComment->getTypeAlias());
            $comment->setThread($parentComment->getThread());
            $comment->setBundle($parentComment->getBundle());
            $comment->setCommentFieldAlias($parentComment->getCommentFieldAlias());
            $comment->setCommentStatus(1);
            $comment->setParent($parentComment);

            try {
                //持久化评论和字段
                $comment = $this->persistSubstance($this->entityManager, $this->fieldConfigRepository,
                    $commentForm, 'comment', $parentComment->getCommentType(), Comment::class);

                $this->addFlash('success', $this->container->get('translator')->trans('teebb.core.comment.create_success'));

                //评论完成，跳转到内容页
                return $this->redirect($request->getSchemeAndHttpHost() . $redirectBackURI);

            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }

        }

        return $this->render($this->templateRegistry->getTemplate('reply_form', 'comment'), [
            'comment_form' => $commentForm->createView(),
            'comment' => $parentComment,
            'action' => 'reply_comment',
            'entity_type' => $this->getEntityTypeService($request),
            'redirectBackURI' => $redirectBackURI
        ]);
    }

    public function updateAction(Request $request, Comment $comment)
    {
        // TODO: Implement updateAction() method.
    }

    /**
     * 删除评论
     * @param Request $request
     * @param Comment $comment
     * @return Response
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function deleteAction(Request $request, Comment $comment)
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
                        $this->deleteSubstance($this->entityManager, $this->fieldConfigRepository, $this->container,
                            'comment', $comment->getCommentType(), $comment);

                        $this->addFlash('success', $this->container->get('translator')->trans(
                            'teebb.core.comment.delete_success', ['%value%' => $comment->getSubject()]
                        ));

                        $conn->commit();

                        //评论删除完成，跳转到redirect页
                        return $this->redirect($request->getSchemeAndHttpHost() . $redirectBackURI);

                    } catch (\Exception $e) {
                        $conn->rollBack();
                        $this->addFlash('danger', $e->getMessage());
                    }

                }
            }
            //如果是取消按钮 跳转到redirect页
            if ($deleteForm->get('cancel')->isClicked()) {
                return $this->redirect($request->getSchemeAndHttpHost() . $redirectBackURI);
            }
        }

        return $this->render($this->templateRegistry->getTemplate('delete_form', 'comment'), [
            'delete_form' => $deleteForm->createView(),
            'comment' => $comment,
            'action' => 'delete_comment'
        ]);
    }

}