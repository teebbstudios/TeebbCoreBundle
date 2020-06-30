<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\StringItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\SimpleValueItem;

class StringFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var StringItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        if ($fieldSettings->isRequired()) {
            $fieldOptions['constraints'] = [
                new NotBlank(),
                new Length([
                    'min' => 1,
                    'max' => $fieldSettings->getLength()
                ])
            ];
        }
        $fieldOptions['label'] = false;
        $fieldOptions['attr'] = [
            'class' => 'form-control form-control-sm col-12 col-sm-6',
            'maxlength' => $fieldSettings->getLength()
        ];
        $builder->add('value', TextType::class, $fieldOptions);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SimpleValueItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }

}