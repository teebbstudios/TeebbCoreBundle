<?php


namespace Teebb\CoreBundle\Form\Type;


use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Todo: 这里可以配置不同的HTML编辑器，以及为ckeditor配置更多的可用options
 */
class TextFormatTextareaType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false
        ]);
    }

    public function getParent()
    {
        return CKEditorType::class;
    }
}