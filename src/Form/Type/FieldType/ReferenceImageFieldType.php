<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Teebb\CoreBundle\Entity\Fields\Configuration\ReferenceImageItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Fields\ReferenceImageItem;
use Teebb\CoreBundle\Form\Type\FileEntityValueType;
use Teebb\CoreBundle\Form\Type\ImageShowType;

class ReferenceImageFieldType extends AbstractType
{
    use FieldConfigOptionsTrait;

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //初次提交数据时设置图像的宽和高
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /**@var ReferenceImageItem $imageItem * */
            $imageItem = $event->getData();
            if ($imageItem->getValue()){
                if ($imageItem->getWidth() == null || $imageItem->getHeight() == null) {
                    $filePath = $imageItem->getValue()->getFilePath();
                    /**@var AbstractAdapter $adapter * */
                    $adapter = $this->filesystem->getAdapter();
                    $absolutePath = $adapter->applyPathPrefix($filePath);

                    list($width, $height) = getimagesize($absolutePath);
                    $imageItem->setWidth($width);
                    $imageItem->setHeight($height);

                    $event->setData($imageItem);
                }
            }
        });

        /**@var FieldConfiguration $fieldConfiguration * */
        $fieldConfiguration = $options['field_configuration'];
        /**@var ReferenceImageItemConfiguration $fieldSettings * */
        $fieldSettings = $options['field_configuration']->getSettings();

        $this->buildCommonFileInputForm($builder, $options, $fieldConfiguration);

        $builder
            ->add('file_show', ImageShowType::class, [
                'label' => false,
                'mapped' => false,
            ])
            ->add('value', FileEntityValueType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'target-file-id-input'
                ]
            ]);

        if ($fieldSettings->isUseAlt()) {
            $builder->add('alt', TextType::class, [
                'label' => 'teebb.core.form.image_alt',
                'help' => 'teebb.core.form.image_alt_help',
                'required' => $fieldSettings->isAltRequired(),
                'constraints' => $fieldSettings->isAltRequired() && $fieldSettings->isRequired() ? [new NotBlank()] : [],
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'row_attr' => [
                    'class' => 'file-other-info-wrapper'
                ],
            ]);
        }

        if ($fieldSettings->isUseTitle()) {
            $builder->add('title', TextType::class, [
                'label' => 'teebb.core.form.image_title',
                'help' => 'teebb.core.form.image_title_help',
                'required' => false,
                'attr' => [
                    'class' => 'form-control-sm'
                ],
                'row_attr' => [
                    'class' => 'file-other-info-wrapper'
                ],
            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReferenceImageItem::class,
            'attr' => [
                'class' => 'p-3 border mb-3 file-upload-wrapper'
            ]
        ]);

        $this->baseConfigOptions($resolver);
    }
}