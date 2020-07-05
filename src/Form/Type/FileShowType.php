<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileShowType extends AbstractType
{
    public function getBlockPrefix()
    {
        return 'file_show';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'show' => false,
            'row_attr'=>[
                'class' => 'file-other-info-wrapper file-show-wrapper'
            ]
        ]);
    }
}