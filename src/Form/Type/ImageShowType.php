<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageShowType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'image_show';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'row_attr'=>[
                'class' => 'file-other-info-wrapper file-show-wrapper'
            ]
        ]);
    }
}