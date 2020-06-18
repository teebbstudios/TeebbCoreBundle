<?php


namespace Teebb\CoreBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * 列表类型字段，将textarea中的值转为array, 或者将array转为textarea中的值,
 * textarea中的格式为xxx|xxx键值对，其他格式键值对解析出错会出现意外情况。
 */
class FieldListTypeAllowValuesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($dataToString) {
                if (null !== $dataToString) {
                    return $this->arrayToText($dataToString);
                }
                return '';
            },
            function ($stringToData) use ($options) {
                return $this->textToArray($stringToData, $options['format']);
            }));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => 'form-control-sm'
            ],
            'constraints' => [
                new NotBlank(),
            ]
        ]);

        $resolver->setDefined('format');
        $resolver->setAllowedValues('format', ['integer', 'float']);
    }

    public function getParent()
    {
        return TextareaType::class;
    }

    private function textToArray(string $text, string $format): array
    {
        $lines = preg_split('/\n|\r\n?/', $text);
        $result = [];
        foreach ($lines as $line) {
            $array = explode('|', $line);
            if (sizeof($array) == 2) {
                $result[$array[0]] = $format == 'float' ? floatval($array[1]) : intval($array[1]);
            } else {
                $result[$line] = $format == 'float' ? floatval($line) : intval($line);
            }
        }
        return $result;
    }

    private function arrayToText(array $array): string
    {
        $result = '';
        $index = 1;
        foreach ($array as $key => $value) {
            $line = $key . '|' . $value;
            $split = sizeof($array) !== $index ? "\r\n" : '';
            $result = $result . $line . $split;
            $index++;
        }
        return $result;
    }
}