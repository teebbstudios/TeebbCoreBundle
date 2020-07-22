<?php


namespace Teebb\CoreBundle\Form\Type\Content;


use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Teebb\CoreBundle\Entity\Taxonomy;

class TaxonomyType extends BaseContentType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //表单提交时设置taxonomyType
        $builder->addEventListener(FormEvents::SUBMIT,
            function (FormEvent $event) use ($options) {
                /**@var Taxonomy $taxonomy * */
                $taxonomy = $event->getData();
                if (null == $taxonomy->getTaxonomyType()) {
                    $taxonomy->setTaxonomyType($options['type_alias']);
                    $event->setData($taxonomy);
                }
            });

        $data = $builder->getData();

        //添加标题字段，所有内容都有一个标题
        $builder
            ->add('term', TextType::class, [
                'label' => 'teebb.core.form.term',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 1, 'max' => 255])
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'teebb.core.form.description',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'required' => false,
                'help' => 'teebb.core.form.term_description_help'
            ])
            ->add('parent', EntityType::class, [
                'label' => 'teebb.core.form.term_parent',
                'label_attr' => [
                    'class' => 'font-weight-bold'
                ],
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'class' => Taxonomy::class,
                'choice_label' => 'term',
                'query_builder' => function (NestedTreeRepository $er) use ($options) {
                    return $er->createQueryBuilder('t')
                        ->where('t.taxonomyType = :taxonomyType')
                        ->setParameter('taxonomyType', $options['type_alias']);
                },
                'placeholder' => 'teebb.core.form.term_root',
                'required' => false,
            ])
            ->add('slug', TextType::class, [
                'label' => 'teebb.core.form.slug',
                'label_attr' => ['class' => 'font-weight-bold'],
                'help' => 'teebb.core.form.slug_help',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'constraints' => [
                    new Regex('/^[a-zA-Z0-9-]+$/')
                ]
            ]);

        $this->dynamicAddFieldForm($builder, $options, $data);
    }
}