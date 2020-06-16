<?php


namespace Teebb\CoreBundle\Form;


use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRegistryInterface;

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
    public function buildEntityTypeForm(FormBuilderInterface $formBuilder, string $entity, array $formRows): FormInterface
    {
        $typeGuesser = $this->formRegistry->getTypeGuesser();
        /**@var FormRowMarkup $formRow * */
        foreach ($formRows as $formRow) {
            if(!property_exists(new $entity, $formRow->getProperty()))
            {
                throw new \RuntimeException(
                    sprintf('The property "%s" is not exist in "%s" when build form. Please check the FormRow annotation.',
                    $formRow->getProperty(), $entity)
                );
            }
            $guessType = $typeGuesser->guessType($entity, $formRow->getProperty());
            $formBuilder->add($formRow->getProperty(), $formRow->getFormType() ?? $guessType, $formRow->getOptions());
        }

        return $formBuilder->getForm();
    }

}