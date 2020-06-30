<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\TextFormatSummaryItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\TextFormatSummaryItem;
use Teebb\CoreBundle\Form\Type\SummaryType;
use Teebb\CoreBundle\Form\Type\TextFormatTextareaType;

class TextFormatSummaryFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var TextFormatSummaryItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        if ($fieldSettings->isRequired()) {
            $fieldOptions['constraints'] = [
                new NotBlank(),
            ];
        }
        $fieldOptions['show_summary'] = $fieldSettings->isShowSummaryInput();
        $fieldOptions['summary_required'] = $fieldSettings->isSummaryRequired() && $fieldSettings->isRequired();
        $fieldOptions['label'] = false;

        $builder
            ->add('summary', SummaryType::class, $fieldOptions)
            ->add('value', TextFormatTextareaType::class, [
                'required' => $fieldSettings->isRequired(),
                'constraints' => $fieldSettings->isRequired() ? [new NotBlank()] : []
            ])
            //->add('formatter', TextFormatterType::class)
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