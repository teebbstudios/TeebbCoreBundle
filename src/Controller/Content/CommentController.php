<?php


namespace Teebb\CoreBundle\Controller\Content;


use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\Response;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
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
        $thread = $request->get('slug');
        $commentType = $request->get('commentType');

        $commentRepo = $this->entityManager->getRepository(Comment::class);

        $comments = $commentRepo->findBy([
            'bundle' => $bundle,
            'typeAlias' => $typeAlias,
            'thread' => $thread,
            'commentType' => $commentType
        ]);

        return $this->render($this->templateRegistry->getTemplate('index', 'comment'), [
            'comments' => $comments
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
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
                    $commentForm, 'comment', $commentType, Comment::class, $comment);

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

    public function updateAction(Request $request, Comment $comment)
    {
        // TODO: Implement updateAction() method.
    }

    public function deleteAction(Request $request, Comment $comment)
    {
        // TODO: Implement deleteAction() method.
    }

}