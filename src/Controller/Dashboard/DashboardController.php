<?php


namespace Teebb\CoreBundle\Controller\Dashboard;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Templating\TemplateRegistry;

class DashboardController extends AbstractController
{
    /**
     * @var TemplateRegistry
     */
    private $templateRegistry;

    public function __construct(TemplateRegistry $templateRegistry)
    {
        $this->templateRegistry = $templateRegistry;
    }

    public function indexAction(Request $request)
    {
        return $this->render($this->templateRegistry->getTemplate('dashboard'), [
        ]);
    }
}