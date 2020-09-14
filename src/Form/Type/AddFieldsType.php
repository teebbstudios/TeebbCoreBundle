<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Contracts\Translation\TranslatorInterface;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Validator\FieldAliasUnique;

/**
 * 添加字段页面选择字段表单
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class AddFieldsType extends AbstractType
{
    /**
     * 所有可用字段数组
     * @var array
     */
    private $fields;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(array $fields, ContainerInterface $container)
    {
        $this->fields = $fields;
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $allFieldsInfo = $this->generateFieldListData();

        $builder
            ->add('select_fields', ChoiceType::class, [
                'choices' => $allFieldsInfo,
                'attr' => [
                    'class' => 'col-12 col-sm-4 select-new-field form-control-sm'
                ],
                'constraints' => [
                    new NotBlank()
                ],
                'placeholder' => '-Please select a field-',
                'help' => 'teebb.core.form.fields.add_fields.help'
            ])
            ->add('field_label', TextType::class, [
                'attr' => [
                    'class' => 'col-12 col-md-6 transliterate form-control-sm'
                ],
                'help' => 'teebb.core.form.fields.field_label.help',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('field_alias', FieldAliasType::class, [
                'attr' => [
                    'class' => 'col-12 col-md-4 input-alias form-control form-control-sm'
                ],
                'help' => 'teebb.core.form.alias_help',
                'constraints' => [
                    new NotBlank(),
                    new FieldAliasUnique(),
                    new Regex(['pattern' => '/^(?!_)(?!.*?_$)[a-zA-Z0-9_]+$/'])
                ],
            ]);
    }

    private function generateFieldListData(): array
    {
        if (empty($this->fields)) {
            throw new \RuntimeException(sprintf('The service "%s" property "fieldList" cannot be empty.', get_class($this)));
        }

        $allFieldsInfo = [];

        foreach ($this->fields as $type => $fieldServices) {
            foreach ($fieldServices as $fieldService) {
                /** @var FieldInterface $field * */
                $field = $this->container->get($fieldService);
                $fieldMetadata = $field->getFieldMetadata();

                $allFieldsInfo[$type][$fieldMetadata->getId()] = $fieldMetadata->getId();
            }
        }
        return $allFieldsInfo;
    }

}