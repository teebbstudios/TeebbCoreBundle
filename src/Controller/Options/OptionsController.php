<?php


namespace Teebb\CoreBundle\Controller\Options;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Form\Type\Options\SystemType;
use Teebb\CoreBundle\Templating\TemplateRegistry;

class OptionsController extends AbstractController
{
    /**
     * @var TemplateRegistry
     */
    private $templateRegistry;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry)
    {
        $this->templateRegistry = $templateRegistry;
        $this->entityManager = $entityManager;
    }

    public function systemAction(Request $request)
    {
        $form = $this->createForm(SystemType::class);

        return $this->render($this->templateRegistry->getTemplate('system', 'options'), [
            'form' => $form->createView()
        ]);
    }
}