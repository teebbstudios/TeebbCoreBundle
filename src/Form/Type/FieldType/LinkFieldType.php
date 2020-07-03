<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\LinkItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\LinkItem;

class LinkFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var LinkItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $builder
            ->add('value', UrlType::class, [
                'label' => 'teebb.core.form.url',
                'help' => 'teebb.core.form.url_help',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm'
                ],
                'required' => $fieldSettings->isRequired(),
                'constraints' => $fieldSettings->isRequired() ? [new NotBlank()] : []
            ])
            ->add('title', TextType::class, [
                'label' => 'teebb.core.form.url_title',
                'help' => 'teebb.core.form.url_title_help',
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm'
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LinkItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }
}