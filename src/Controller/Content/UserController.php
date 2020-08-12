<?php


namespace Teebb\CoreBundle\Controller\Content;


use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Entity\Types\Types;


/**
 * user entity controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class UserController extends AbstractContentController
{
    public function indexAction(Request $request)
    {
        // TODO: Implement indexAction() method.
    }

    public function createAction(Request $request, Types $types)
    {
        // TODO: Implement createAction() method.
    }

    public function updateAction(Request $request, Taxonomy $content)
    {
        // TODO: Implement updateAction() method.
    }

    public function deleteAction(Request $request, Taxonomy $content)
    {
        // TODO: Implement deleteAction() method.
    }
}