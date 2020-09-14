<?php


namespace Teebb\CoreBundle\Form\Type;


use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Todo: ckeditor 工具栏需要根据过滤器的更改而更改。新版本再增加
 * CKEDITOR编辑器
 */
class TextFormatTextareaType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
        ]);
    }

    public function getParent()
    {
        return CKEditorType::class;
    }
}