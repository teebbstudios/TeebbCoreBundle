<?php


namespace Teebb\CoreBundle\Controller\Content;


use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;
use Teebb\CoreBundle\Exception\CantReplyCommentException;
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
        $allowReply = $request->get('allowReply');

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
            'redirectBackURI' => $redirectBackURI,
            'allowReply' => $allowReply
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
                    $this->eventDispatcher, $this->container,
                    $commentForm, 'comment', $commentType, Comment::class);

                $this->addFlash('success', $this->container->get('translator')->trans('teebb.core.comment.create_success'));

                //评论完成，跳转到内容页
                return $this->redirect($redirectBackURI);

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
    public function replyComment(Request $request, Comment $parentComment): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

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

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
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
                    $this->eventDispatcher, $this->container,
                    $commentForm, 'comment', $parentComment->getCommentType(), Comment::class);

                $this->addFlash('success', $this->container->get('translator')->trans('teebb.core.comment.create_success'));

                //评论完成，跳转到内容页
                return $this->redirect($redirectBackURI);

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

}