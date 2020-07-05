<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceImageItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Fields\ReferenceImageItem;
use Teebb\CoreBundle\Form\Type\FileEntityValueType;
use Teebb\CoreBundle\Form\Type\ImageShowType;

class ReferenceImageFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var FieldConfiguration $fieldConfiguration * */
        $fieldConfiguration = $options['field_configuration'];
        /**@var ReferenceImageItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $this->buildCommonFileInputForm($builder, $options, $fieldConfiguration);

        $builder
            ->add('file_show', ImageShowType::class, [
                'label' => false,
                'mapped' => false,
            ])
            ->add('value', FileEntityValueType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'target-file-id-input'
                ]
            ]);

        if ($fieldSettings->isUseAlt()) {
            $builder->add('alt', TextType::class, [
                'label' => 'teebb.core.form.image_alt',
                'help' => 'teebb.core.form.image_alt_help',
                'required' => $fieldSettings->isAltRequired(),
                'constraints' => $fieldSettings->isAltRequired() && $fieldSettings->isRequired() ? [new NotBlank()] : [],
                'attr' => [
                    'class' => 'form-control form-control-sm'
                ],
                'row_attr' => [
                    'class' => 'file-other-info-wrapper'
                ],
            ]);
        }

        if ($fieldSettings->isUseTitle()) {
            $builder->add('title', TextType::class, [
                'label' => 'teebb.core.form.image_title',
                'help' => 'teebb.core.form.image_title_help',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-sm'
                ],
                'row_attr' => [
                    'class' => 'file-other-info-wrapper'
                ],
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceImageItem::class,
            'attr' => [
                'class' => 'col-12 col-sm-6 p-3 border mb-3 file-upload-wrapper'
            ]
        ]);

        $this->baseConfigOptions($resolver);
    }
}