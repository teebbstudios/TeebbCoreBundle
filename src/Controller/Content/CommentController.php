<?php


namespace Teebb\CoreBundle\Controller\Content;


use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Comment;
use Teebb\CoreBundle\Entity\Types\Types;
use Symfony\Component\HttpFoundation\Response;

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
        $thread = $request->get('thread');
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

    public function createAction(Request $request, Types $types)
    {

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