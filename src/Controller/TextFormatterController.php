<?php


namespace Teebb\CoreBundle\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\Entity\TextFormat\Formatter;
use Teebb\CoreBundle\Form\FormContractorInterface;
use Teebb\CoreBundle\Form\Type\FormatterType;
use Teebb\CoreBundle\Templating\TemplateRegistry;
use Symfony\Component\HttpFoundation\Response;

/**
 * 文本格式化器控制器
 */
class TextFormatterController extends AbstractController
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
     * @var FormContractorInterface
     */
    private $formContractor;

    public function __construct(EntityManagerInterface $entityManager, TemplateRegistry $templateRegistry,
                                FormContractorInterface $formContractor)
    {
        $this->templateRegistry = $templateRegistry;
        $this->entityManager = $entityManager;
        $this->formContractor = $formContractor;
    }

    public function indexAction(Request $request)
    {
        $formatterRepo = $this->entityManager->getRepository(Formatter::class);

        $formatters = $formatterRepo->findAll();

        return $this->render($this->templateRegistry->getTemplate('index', 'formatter'), [
            'formatters' => $formatters,
            'action' => 'index'
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(FormatterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Formatter $formatter * */
            $formatter = $form->getData();
            $this->entityManager->persist($formatter);
            $this->entityManager->flush();

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.formatter.create_success', ['%value%' => $formatter->getName()]
            ));

            return $this->redirectToRoute('teebb_formatter_index');
        }
        return $this->render($this->templateRegistry->getTemplate('create', 'formatter'), [
            'form' => $form->createView(),
            'extra_assets' => ['transliteration'], //当前页面需要额外添加的assets库
            'action' => 'create'
        ]);
    }

    /**
     * @param Request $request
     * @param Formatter $formatter
     * @return Response
     */
    public function updateAction(Request $request, Formatter $formatter)
    {
        $form = $this->createForm(FormatterType::class, $formatter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var Formatter $formatter * */
            $formatter = $form->getData();
            $this->entityManager->persist($formatter);
            $this->entityManager->flush();

            $this->addFlash('success', $this->container->get('translator')->trans(
                'teebb.core.formatter.update_success', ['%value%' => $formatter->getName()]
            ));

            return $this->redirectToRoute('teebb_formatter_index');
        }
        return $this->render($this->templateRegistry->getTemplate('update', 'formatter'), [
            'form' => $form->createView(),
            'formatter' => $formatter,
            'action' => 'update'
        ]);
    }

    /**
     * @param Request $request
     * @param Formatter $formatter
     * @return Response
     */
    public function deleteAction(Request $request, Formatter $formatter)
    {
        $form = $this->formContractor->generateDeleteForm($request->get('_route'), FormType::class, $formatter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('_method')->getData() == 'DELETE'){
                $this->entityManager->remove($formatter);
                $this->entityManager->flush();

                $this->addFlash('success', $this->container->get('translator')->trans(
                    'teebb.core.formatter.delete_success', ['%value%' => $formatter->getName()]
                ));

                return $this->redirectToRoute('teebb_formatter_index');
            }
        }

        return $this->render($this->templateRegistry->getTemplate('delete', 'formatter'), [
            'delete_form' => $form->createView(),
            'formatter' => $formatter,
            'action' => 'delete'
        ]);
    }
}