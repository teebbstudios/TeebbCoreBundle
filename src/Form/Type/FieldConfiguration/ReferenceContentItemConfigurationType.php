<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceContentItemConfiguration;
use Teebb\CoreBundle\Form\Type\FieldReferenceEntityType;
use Teebb\CoreBundle\Repository\Types\EntityTypeRepository;

class ReferenceContentItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('referenceTypes', FieldReferenceEntityType::class, [
            'label' => 'teebb.core.fields.configuration.reference_content',
            'query_builder' => function (EntityTypeRepository $er) {
                $qb = $er->createQueryBuilder('t');
                return $qb->select('t')
                    ->andWhere($qb->expr()->eq('t.bundle', ':bundle'))
                    ->setParameter('bundle', 'types')
                    ->orderBy('t.id', 'ASC');
            },
            'help' => 'teebb.core.fields.configuration.reference_content_help',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceContentItemConfiguration::class
        ]);
    }

}