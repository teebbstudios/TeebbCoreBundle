<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;

class FieldSortableDisplayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $fieldConfigurations = $builder->getData();
        /**@var FieldConfiguration $fieldConfiguration * */
        foreach ($fieldConfigurations as $fieldConfiguration) {
            $builder->add($fieldConfiguration->getFieldAlias(), FieldSortableDisplayRowType::class, [
                'label' => $fieldConfiguration->getFieldLabel(),
                'data' => $fieldConfiguration->getDelta(),
                'attr' => [
                    'class' => 'input-field-weight form-control form-control-sm w-auto'
                ],
                'row_attr' => [
                    'class' => 'js-sortable-item'
                ],
                'field_type' => $fieldConfiguration->getFieldType(),
                'field_alias' => $fieldConfiguration->getFieldAlias(),
                'type_alias' => $fieldConfiguration->getTypeAlias()
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'table table-striped'
            ]
        ]);

    }

}