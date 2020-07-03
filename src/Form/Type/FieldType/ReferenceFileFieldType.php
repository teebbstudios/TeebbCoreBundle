<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceFileItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\ReferenceFileItem;

class ReferenceFileFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /**@var ReferenceFileItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

//        $fieldOptions = $this->configFileFieldOptions($fieldSettings);

        $builder
            ->add('file', FileType::class, [
                'label' => false,
                'help' => 'teebb.core.form.file_upload_help',
                'help_translation_parameters'=>[
                    '%ext%' => implode(',', $fieldSettings->getAllowExt()),
                    '%size%' => $fieldSettings->getMaxSize()?: ini_get('upload_max_filesize')
                ],
                'mapped' => false,
                'row_attr' => [
                    'class' => 'col-12 col-sm-6 pl-0'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => $fieldSettings->getMaxSize() ?: ini_get('upload_max_filesize'),
                        'mimeTypes' => $this->getExtMimeTypes($fieldSettings->getAllowExt()),
                    ])
                ]
            ])
            //            ->add('entity', ReferenceFileType:class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceFileItem::class,
        ]);

        $this->baseConfigOptions($resolver);
    }

    public function getExtMimeTypes(array $exts){
        $mimeTypes = new MimeTypes();

        $extMimeTypeArray = [];
        foreach ($exts as $ext) {
            $result = $mimeTypes->getMimeTypes($ext);
            if (!empty($result)) {
                array_walk_recursive($result, function($value) use (&$extMimeTypeArray) {
                    array_push($extMimeTypeArray, $value);
                });
            }
        }
        return $extMimeTypeArray;
    }
}