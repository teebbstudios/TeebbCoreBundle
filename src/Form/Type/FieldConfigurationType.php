<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;

/**
 * 编辑字段表单
 */
class FieldConfigurationType extends AbstractType
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bundle', HiddenType::class)
            ->add('typeAlias', HiddenType::class)
            ->add('fieldLabel', TextType::class, [
                'attr' => [
                    'class' => 'col-12 col-sm-6 form-control-sm'
                ],
                'help' => 'teebb.core.fields.configuration.label_help'
            ])
            ->add('fieldAlias', AliasValueType::class, [
                'help' => 'teebb.core.form.alias_help'
            ])
            ->add('fieldType', HiddenType::class);

        //根据字段类型动态添加字段设置表单
        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($builder) {
                /**@var FieldConfiguration $data * */
                if (null !== $data = $event->getData()) {
                    $fieldType = $data->getFieldType();
                    /**@var FieldInterface $fieldService * */
                    $fieldService = $this->container->get('teebb.core.field.' . $fieldType);
                    if (null === $fieldService) {
                        throw new ServiceNotFoundException(sprintf('Field Service "%s" does not exist.', 'teebb.core.field.' . $fieldType));
                    }
                    $FormType = $fieldService->getFieldConfigType();

                    $event->getForm()->add('settings', $FormType, [
                        'label' => 'teebb.core.fields.configuration.settings'
                    ]);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FieldConfiguration::class
        ]);
    }
}