<?php


namespace Teebb\CoreBundle\Form\Type\FieldConfiguration;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\CommentItemConfiguration;
use Teebb\CoreBundle\Entity\Types\Types;
use Teebb\CoreBundle\Repository\Types\EntityTypeRepository;

class CommentItemConfigurationType extends BaseItemConfigurationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //设置字段数量限制为1及设置为必填
        $this->setFieldRequiredAndLimitOne($builder, $options, true);

        parent::buildForm($builder, $options);
        $builder
            ->add('commentType', EntityType::class, [
                'label' => 'teebb.core.fields.configuration.comment_type',
                'class' => Types::class,
                'choice_label' => 'label',
                'choice_value' => 'typeAlias',
                'query_builder' => function (EntityTypeRepository $repository) {
                    return $repository->createQueryBuilder('c')
                        ->where('c.bundle=:bundle')
                        ->setParameter('bundle', 'comment');
                },
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm'
                ],
                'constraints' => [
                    new NotBlank()
                ],
                'help' => 'teebb.core.fields.configuration.comment_type_help',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentItemConfiguration::class
        ]);
    }
}