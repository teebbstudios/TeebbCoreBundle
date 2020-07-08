<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceFileItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Fields\ReferenceFileItem;
use Teebb\CoreBundle\Form\Type\FileEntityValueType;
use Teebb\CoreBundle\Form\Type\FileShowType;

class ReferenceFileFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var FieldConfiguration $fieldConfiguration * */
        $fieldConfiguration = $options['field_configuration'];
        /**@var ReferenceFileItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $this->buildCommonFileInputForm($builder, $options, $fieldConfiguration);

        $builder
            ->add('file_show', FileShowType::class, [
                'label' => false,
                'mapped' => false,
            ])
            ->add('value', FileEntityValueType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'target-file-id-input'
                ]
            ]);

        if ($fieldSettings->isShowControl()) {
            $builder->add('showFile', CheckboxType::class, [
                'label' => 'teebb.core.form.show_file',
                'required' => false,
                'row_attr' => [
                    'class' => 'file-other-info-wrapper'
                ],
                'data' => $fieldSettings->isShowControl(),
            ]);
        }

        $builder->add('description', TextType::class, [
            'label' => 'teebb.core.form.file_description',
            'help' => 'teebb.core.form.file_description_help',
            'attr' => [
                'class' => 'form-control-sm'
            ],
            'row_attr' => [
                'class' => 'file-other-info-wrapper'
            ],
            'required' => $fieldSettings->isUseDescription(),
            'constraints' => $fieldSettings->isUseDescription() && $fieldSettings->isRequired() ? [new NotBlank()] : [],
            'empty_data' => ''
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceFileItem::class,
            'attr'=>[
                'class' => 'p-3 border mb-3 file-upload-wrapper'
            ]
        ]);

        $this->baseConfigOptions($resolver);
    }

}