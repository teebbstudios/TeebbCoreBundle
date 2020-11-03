<?php


namespace Teebb\CoreBundle\Controller\Options;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\Option;
use Teebb\CoreBundle\Entity\Options\System;
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

    /**
     * @var ObjectRepository
     */
    private $optionRepository;

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry)
    {
        $this->templateRegistry = $templateRegistry;
        $this->entityManager = $entityManager;
        $this->optionRepository = $this->entityManager->getRepository(Option::class);
    }

    public function systemAction(Request $request)
    {
        $this->denyAccessUnlessGranted("system_update");

        /**@var Option $systemOption * */
        $systemOption = $this->optionRepository->findOneBy(['optionName' => 'system']);

        /**@var System $system * */
        $system = $systemOption->getOptionValue();
        $form = $this->createForm(SystemType::class, $system);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var System $data * */
            $data = $form->getData();
            $systemOption->setOptionValue(clone $data);

            $this->entityManager->persist($systemOption);
            $this->entityManager->flush();

            $this->addFlash('success', $this->container->get('translator')->trans('teebb.core.form.system_update_success'));
        }

        return $this->render($this->templateRegistry->getTemplate('system', 'options'), [
            'form' => $form->createView()
        ]);
    }
}