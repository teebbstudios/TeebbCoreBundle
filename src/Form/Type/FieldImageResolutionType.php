<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * 图片字段宽高限制字段
 */
class FieldImageResolutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('width', IntegerType::class, [
                'label' => 'teebb.core.fields.configuration.image_width',
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'required' => false,
                'row_attr' => [
                    'class' => 'col-12 col-sm-4'
                ]
            ])
            ->add('height', IntegerType::class, [
                'label' => 'teebb.core.fields.configuration.image_height',
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'required' => false,
                'row_attr' => [
                    'class' => 'col-12 col-sm-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'form-row'
            ]
        ]);
    }


}