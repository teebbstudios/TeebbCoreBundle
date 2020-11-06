<?php


namespace Teebb\CoreBundle\Controller\Dashboard;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Templating\TemplateRegistry;

class DashboardController extends AbstractController
{
    /**
     * @var TemplateRegistry
     */
    private $templateRegistry;
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(TemplateRegistry $templateRegistry, ParameterBagInterface $parameterBag)
    {
        $this->templateRegistry = $templateRegistry;
        $this->parameterBag = $parameterBag;
    }

    public function indexAction(Request $request)
    {
        $blocks = [
            'top' => [],
            'left' => [],
            'center' => [],
            'right' => [],
            'bottom' => [],
        ];

        $dashboardBlocks = $this->parameterBag->get('teebb.core.dashboard.blocks');

        foreach ($dashboardBlocks as $block) {
            $blocks[$block['position']][] = $block;
        }

        return $this->render($this->templateRegistry->getTemplate('dashboard'), [
            'blocks' => $blocks
        ]);
    }
}