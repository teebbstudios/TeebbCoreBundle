<?php


namespace Teebb\CoreBundle\Controller\Options;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Teebb\CoreBundle\Entity\Option;
use Teebb\CoreBundle\Entity\Options\OptionInterface;
use Teebb\CoreBundle\Entity\Options\System;
use Teebb\CoreBundle\Event\OptionCacheEvent;
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
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManager,
                                EventDispatcherInterface $eventDispatcher,
                                TemplateRegistry $templateRegistry)
    {
        $this->templateRegistry = $templateRegistry;
        $this->entityManager = $entityManager;
        $this->optionRepository = $this->entityManager->getRepository(Option::class);
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Request $request
     * @param Option $option
     * @return Response
     */
    public function __invoke(Request $request, Option $option): Response
    {
        $this->denyAccessUnlessGranted($option->getOptionName() . "_update");

        /**@var OptionInterface $optionValue * */
        $optionValue = $option->getOptionValue();
        $form = $this->createForm($option->getOptionType(), $optionValue);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var OptionInterface $data * */
            $data = $form->getData();
            $option->setOptionValue(clone $data);

            $this->entityManager->persist($option);
            $this->entityManager->flush();

            //删除缓存
            $optionEvent = new OptionCacheEvent();
            $optionEvent->setOptionName($option->getOptionName());
            $this->eventDispatcher->dispatch($optionEvent, OptionCacheEvent::DELETE_OPTION_CACHE);

            $this->addFlash('success',
                $this->container->get('translator')->trans(
                    'teebb.core.form.option_update_success', [
                        '%optionLabel%' => $option->getOptionLabel()
                    ]
                ));
        }

        return $this->render($this->templateRegistry->getTemplate('option_form', 'options'), [
            'form' => $form->createView(),
            'option' => $option
        ]);
    }
}