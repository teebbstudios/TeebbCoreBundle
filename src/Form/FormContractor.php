<?php


namespace Teebb\CoreBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRegistryInterface;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\AliasValueType;

/**
 * Class FormContractor
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class FormContractor implements FormContractorInterface
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var FormRegistryInterface
     */
    private $formRegistry;

    public function __construct(FormFactoryInterface $formFactory, FormRegistryInterface $formRegistry)
    {
        $this->formFactory = $formFactory;
        $this->formRegistry = $formRegistry;
    }

    /**
     * @inheritDoc
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->formFactory;
    }

    /**
     * @inheritDoc
     */
    public function getFormBuilder(string $name, string $type = FormType::class, $data = null, $options = []): FormBuilderInterface
    {
        return $this->formFactory->createNamedBuilder($name, $type, $data, $options);
    }

    /**
     * @inheritDoc
     */
    public function buildEntityTypeForm(FormBuilderInterface $formBuilder, string $entity, array $formRows, string $bundle): FormInterface
    {
        $data = $formBuilder->getData();
        $typeGuesser = $this->formRegistry->getTypeGuesser();
        /**@var FormRowMarkup $formRow **/
        foreach ($formRows as $formRow) {
            if (!property_exists(new $entity, $formRow->getProperty())) {
                throw new \RuntimeException(
                    sprintf('The property "%s" is not exist in "%s" when build form. Please check the FormRow annotation.',
                        $formRow->getProperty(), $entity)
                );
            }

            $guessType = $typeGuesser->guessType($entity, $formRow->getProperty());

            //当前为编辑表单时，如果修改别名行Type为AliasValueType
            if (null !== $data && $formRow->isAlias()) {
                $type = AliasValueType::class;
            } else {
                $type = $formRow->getFormType() ?? $guessType;
            }

            $formBuilder->add($formRow->getProperty(), $type, $formRow->getOptions());
        }

        //如果是创建表单页面，处理表单bundle行 hidden input value.
        $formBuilder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($bundle) {
                if (null == $event->getData()) {
                    $data = new Types();
                    $data->setBundle($bundle);
                    $event->setData($data);
                }
            }
        );

        return $formBuilder->getForm();
    }

}