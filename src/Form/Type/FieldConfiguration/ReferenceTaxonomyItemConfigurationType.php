<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceTaxonomyItemConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Form\Type\FieldConfigurationReferenceEntityType;
use Teebb\CoreBundle\Repository\Types\EntityTypeRepository;

class ReferenceTaxonomyItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('referenceTypes', FieldConfigurationReferenceEntityType::class, [
                'label' => 'teebb.core.fields.configuration.reference_taxonomy',
                'query_builder' => function (EntityTypeRepository $er) {
                    $qb = $er->createQueryBuilder('t');
                    return $qb->select('t')
                        ->andWhere($qb->expr()->eq('t.bundle', ':bundle'))
                        ->setParameter('bundle', 'taxonomy')
                        ->orderBy('t.id', 'ASC');
                },
                'help' => 'teebb.core.fields.configuration.reference_taxonomy_help',
            ])
            ->add('autoCreateToType', EntityType::class, [
                'label' => 'teebb.core.fields.configuration.auto_create_to_type',
                'class' => Types::class,
                'query_builder' => function (EntityTypeRepository $er) {
                    $qb = $er->createQueryBuilder('t');
                    return $qb->select('t')
                        ->andWhere($qb->expr()->eq('t.bundle', ':bundle'))
                        ->setParameter('bundle', 'taxonomy')
                        ->orderBy('t.id', 'ASC');
                },
                'attr' => [
                    'class' => 'col-12 col-sm-4 form-control-sm'
                ],
                'constraints' => [
                    new NotBlank()
                ],
                'choice_label' => 'label',
                'choice_value' => 'typeAlias',
                'help' => 'teebb.core.fields.configuration.auto_create_to_type_help',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceTaxonomyItemConfiguration::class
        ]);
    }

}