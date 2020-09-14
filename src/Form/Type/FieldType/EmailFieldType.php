<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\EmailItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\EmailItem;

class EmailFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->transformSubmitNullDataToObject($builder, $options);

        /**@var EmailItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $fieldOptions = [
            'label' => false,
            'attr' => [
                'class' => 'form-control-sm'
            ]
        ];
        
        if ($fieldSettings->isRequired()) {
            $fieldOptions['constraints'] = [
                new NotBlank(),
            ];
        }

        $builder->add('value', EmailType::class, $fieldOptions);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmailItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}