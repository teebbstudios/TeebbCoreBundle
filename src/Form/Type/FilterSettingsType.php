<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $filterSetting = $event->getData();
            foreach ($filterSetting as $filterName => $filterValue) {
                if (false === $filterValue['filter_name']) {
                    unset($filterSetting[$filterName]);

                    $event->setData($filterSetting);
                }
            }
        });

        foreach ($options['filter_settings'] as $filterName => $filterSetting) {
            $filterOptions = [
                'label' => false,
                'filter_label' => $filterSetting['filter_label'],
            ];

            if (isset($filterSetting['extra_form_type'])) {
                $filterOptions['extra_form_type'] = $filterSetting['extra_form_type'];
            }
            if (isset($filterSetting['extra_label'])) {
                $filterOptions['extra_label'] = $filterSetting['extra_label'];
            }
            if (isset($filterSetting['extra_help'])) {
                $filterOptions['extra_help'] = $filterSetting['extra_help'];
            }

            $builder->add($filterName, FilterRowType::class, $filterOptions);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('filter_settings');
    }

}