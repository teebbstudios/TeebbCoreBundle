<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\ListIntegerItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\ReferenceContentItem;
use Teebb\CoreBundle\Entity\Fields\ReferenceEntityItem;

//Todo: 引用类型最后处理
class ReferenceContentFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var ListIntegerItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceContentItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}