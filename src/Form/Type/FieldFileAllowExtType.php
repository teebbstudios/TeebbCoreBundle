<?php


namespace Teebb\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Teebb\CoreBundle\Validator\FieldFileExtIsRightMime;

/**
 * 文件、图片类型字段允许的后缀Type
 */
class FieldFileAllowExtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
                function ($arrayToString) {
                    if (is_array($arrayToString)) {
                        return implode(',', $arrayToString);
                    }
                    return $arrayToString;
                },
                function ($stringToArray) {
                    return explode(',', $stringToArray == '' ? 'txt' : $stringToArray);
                }
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['class' => 'col-12 col-sm-6 form-control form-control-sm input-allow-extension-name'],
            'constraints' => [
                new Count(['min' => 1]),
                new FieldFileExtIsRightMime()
            ]
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }

}