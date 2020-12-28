<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceContentItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\ReferenceContentItem;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\FieldReferenceEntityType;

/**
 * 引用内容字段
 */
class ReferenceContentFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var ReferenceContentItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $referenceTypes = [];
        /**@var Types $types * */
        foreach ($fieldSettings->getReferenceTypes() as $types) {
            array_push($referenceTypes, $types->getTypeAlias());
        }

        $builder->add('value', FieldReferenceEntityType::class, [
            'find_label' => 'title',
            'entity_class' => $fieldSettings->getReferenceTargetEntity(),
            'type_label' => 'typeAlias',
            'reference_types' => $referenceTypes,
            'data_autocomplete_route' => 'teebb_content_substances_api'
        ]);

        //如果不限制字段数量则添加删除当前行按钮
        $this->addRemoveFieldButton($builder, $options['field_configuration'], $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceContentItem::class,
            'attr' => [
                'class' => 'position-relative'
            ]
        ]);

        $this->baseConfigOptions($resolver);
    }
}