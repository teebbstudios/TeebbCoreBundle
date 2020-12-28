<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceTaxonomyItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\ReferenceUserItem;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\FieldReferenceEntityType;

class ReferenceUserFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var ReferenceTaxonomyItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $referenceTypes = ['people'];

        $builder->add('value', FieldReferenceEntityType::class, [
            'find_label' => 'email',
            'entity_class' => $fieldSettings->getReferenceTargetEntity(),
            'reference_types' => $referenceTypes,
            'data_autocomplete_route' => 'teebb_user_substances_api',
        ]);

        //如果不限制字段数量则添加删除当前行按钮
        $this->addRemoveFieldButton($builder, $options['field_configuration'], $options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceUserItem::class,
            'attr' => [
                'class' => 'position-relative'
            ]
        ]);

        $this->baseConfigOptions($resolver);
    }
}