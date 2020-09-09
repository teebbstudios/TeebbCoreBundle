<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceTaxonomyItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\ReferenceTaxonomyItem;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\FieldReferenceEntityType;

class ReferenceTaxonomyFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var ReferenceTaxonomyItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $referenceTypes = [];
        /**@var Types $types**/
        foreach ($fieldSettings->getReferenceTypes() as $types){
            array_push($referenceTypes,  $types->getTypeAlias());
        }

        $builder->add('value', FieldReferenceEntityType::class, [
            'find_label' => 'term',
            'entity_class' => $fieldSettings->getReferenceTargetEntity(),
            'type_label' => 'taxonomyType',
            'reference_types' => $referenceTypes,
            'data_autocomplete_route' => 'teebb_taxonomy_substances_api'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceTaxonomyItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}