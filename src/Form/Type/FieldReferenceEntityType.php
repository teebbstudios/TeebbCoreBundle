<?php


namespace Teebb\CoreBundle\Form\Type;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Teebb\CoreBundle\Form\DataTransformer\FindLabelToEntityTransformer;

/**
 * 字段引用实体类型表单
 */
class FieldReferenceEntityType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $referenceEntityClass = $options['entity_class'];
        $findLabel = $options['find_label'];

        $referenceTypes = []; //如果引用的用户则不需要引用的类型了，留空数组
        $typeLabel = null;

        if (isset($options['reference_types'])) {
            $referenceTypes = $options['reference_types'];
        }

        if (isset($options['type_label'])) {
            $typeLabel = $options['type_label'];
        }

        $builder->addModelTransformer(new FindLabelToEntityTransformer(
            $this->entityManager,
            $referenceEntityClass,
            $findLabel,
            $typeLabel,
            $referenceTypes
        ));
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = $view->vars['attr'];
        $class = isset($attr['class']) ? $attr['class'] . ' ' : '';
        $class .= 'js-reference-entity-autocomplete';

        $attr['class'] = $class;
        $attr['data-find-label'] = $options['find_label'];
        $attr['data-reference-types'] = implode(',',$options['reference_types']);
        $attr['data-type-label'] = $options['type_label'];
        $attr['data-autocomplete-url'] = $this->router->generate($options['data_autocomplete_route']);
        $view->vars['attr'] = $attr;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'attr' => [
                'class' => 'form-control-sm',
                'data-autocomplete' => '0',
            ],
            'invalid_message' => 'Hmm, not found that. Check it!',
        ]);

        //引用的实体类全类名，用于查询
        $resolver->setDefined('entity_class');

        //引用的实体类属性，用于查询的参数名
        $resolver->setDefined('find_label');

        //用于查询结果的URL
        $resolver->setDefined('data_autocomplete_route');

        //用于引用内容、分类字段查询条件，引用的哪些类型
        $resolver->setDefined('reference_types');
        //用于引用内容、分类查询条件label
        $resolver->setDefined('type_label');

        $resolver->setRequired('entity_class');
        $resolver->setRequired('find_label');
        $resolver->setRequired('data_autocomplete_route');
    }


    public function getParent()
    {
        return TextType::class;
    }
}