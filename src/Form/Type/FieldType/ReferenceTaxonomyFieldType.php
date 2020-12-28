<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceTaxonomyItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Fields\ReferenceTaxonomyItem;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\FieldReferenceEntityType;
use Teebb\CoreBundle\Form\Type\RemoveFieldItemButtonType;

class ReferenceTaxonomyFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var FieldConfiguration $fieldConfig * */
        $fieldConfig = $options['field_configuration'];
        /**@var ReferenceTaxonomyItemConfiguration $fieldSettings * */
        $fieldSettings = $fieldConfig->getSettings();

        $referenceTypes = [];
        /**@var Types $types * */
        foreach ($fieldSettings->getReferenceTypes() as $types) {
            array_push($referenceTypes, $types->getTypeAlias());
        }

        $builder
            ->add('value', FieldReferenceEntityType::class, [
                'find_label' => 'term',
                'entity_class' => $fieldSettings->getReferenceTargetEntity(),
                'type_label' => 'taxonomyType',
                'reference_types' => $referenceTypes,
                'data_autocomplete_route' => 'teebb_taxonomy_substances_api',
                'auto_create_to_type' => $fieldSettings->getAutoCreateToType(),
            ]);

        //如果不限制字段数量则添加删除当前行按钮
        $this->addRemoveFieldButton($builder, $fieldConfig, $options);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceTaxonomyItem::class,
            'attr' => [
                'class' => 'position-relative'
            ]
        ]);

        $this->baseConfigOptions($resolver);
    }
}