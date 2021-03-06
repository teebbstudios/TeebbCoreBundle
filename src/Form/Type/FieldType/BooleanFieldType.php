<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\BooleanItem;
use Teebb\CoreBundle\Entity\Fields\Configuration\BooleanItemConfiguration;

class BooleanFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var BooleanItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $choices = [$fieldSettings->getOnLabel() => true, $fieldSettings->getOffLabel() => false];

        $fieldOptions = [
            'label' => false,
            'choices' => $choices,
            'multiple' => false,
            'expanded' => true,
            'attr' => [
                'class' => 'form-check-inline'
            ],
        ];

        $builder->add('value', ChoiceType::class, $fieldOptions);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BooleanItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}