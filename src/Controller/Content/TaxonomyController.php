<?php


namespace Teebb\CoreBundle\Controller\Content;


use Pagerfanta\Pagerfanta;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\Content\ContentBatchOptionsType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Teebb\CoreBundle\Repository\BaseContentRepository;


/**
 * 分类entity controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class TaxonomyController extends AbstractContentController
{
    public function indexAction(Request $request)
    {
        // TODO: Implement indexAction() method.
    }

    public function createAction(Request $request, Types $types)
    {
        // TODO: Implement createAction() method.
    }

    public function updateAction(Request $request, Content $content)
    {
        // TODO: Implement updateAction() method.
    }

    public function deleteAction(Request $request, Content $content)
    {
        // TODO: Implement deleteAction() method.
    }
}