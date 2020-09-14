<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\TextFormatSummaryItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\TextFormatSummaryItem;
use Teebb\CoreBundle\Entity\Formatter;
use Teebb\CoreBundle\Form\Type\SummaryType;
use Teebb\CoreBundle\Form\Type\TextFormatterType;
use Teebb\CoreBundle\Form\Type\TextFormatTextareaType;

class TextFormatSummaryFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $formatterRepo;

    /**
     * @var array
     */
    private $filterSettings;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->formatterRepo = $this->container->get('doctrine.orm.default_entity_manager')->getRepository(Formatter::class);
        $this->filterSettings = $this->container->getParameter('teebb.core.formatter.filter_settings');
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**
         * 格式化器过滤提交的文本
         */
        $this->filterFormatFieldValue($builder, $this->formatterRepo, $this->filterSettings);

        $this->transformSubmitNullDataToObject($builder, $options);

        /**@var TextFormatSummaryItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        if ($fieldSettings->isSummaryRequired() && $fieldSettings->isRequired()) {
            $fieldOptions['constraints'] = [
                new NotBlank(),
            ];
        }

        //提交表单时如果摘要不是必填，且摘要为空则自动生成摘要
        $builder->addEventListener(FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($fieldSettings) {
                if (!$fieldSettings->isSummaryRequired()) {
                    $data = $event->getData();
                    if (empty($data['summary'])) {
                        $stripContent = strip_tags($data['value']);
                        $count = $fieldSettings->getSummaryLength();
                        $data['summary'] = mb_substr($stripContent, 0, $count, 'UTF-8');
                        if (mb_strlen($stripContent, 'UTF-8') > $count) {
                            $data['summary'] = $data['summary'] . "...";
                        }
                        $event->setData($data);
                    }
                }
            });


        $fieldOptions['show_summary'] = $fieldSettings->isShowSummaryInput();
        $fieldOptions['summary_required'] = $fieldSettings->isSummaryRequired() && $fieldSettings->isRequired();
        $fieldOptions['required'] = $fieldSettings->isSummaryRequired() && $fieldSettings->isRequired();
        $fieldOptions['label'] = false;

        $builder
            ->add('summary', SummaryType::class, $fieldOptions)
            ->add('value', TextFormatTextareaType::class, [
                'required' => $fieldSettings->isRequired(),
                'constraints' => $fieldSettings->isRequired() ? [new NotBlank()] : []
            ])
            ->add('formatter', TextFormatterType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TextFormatSummaryItem::class
        ]);

        $this->baseConfigOptions($resolver);
    }

}