<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\Entity\Fields\CommentItem;
use Teebb\CoreBundle\Entity\Fields\Configuration\CommentItemConfiguration;

class CommentFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var CommentItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $choices = ['teebb.core.form.comment_on' => 1, 'teebb.core.form.comment_off' => 0];

        $fieldOptions = [
            'label' => false,
            'choices' => $choices,
            'multiple' => false,
            'expanded' => true,
            'attr' => [
                'class' => 'form-check-inline'
            ],
            'help' => 'teebb.core.form.comment_on_off_help',
        ];

        $builder->add('value', ChoiceType::class, $fieldOptions);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CommentItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}