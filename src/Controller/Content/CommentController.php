<?php


namespace Teebb\CoreBundle\Controller\Content;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\ContentType;
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;
use Teebb\CoreBundle\Repository\Types\EntityTypeRepository;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;


/**
 * 评论entity controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class CommentController extends AbstractContentController
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