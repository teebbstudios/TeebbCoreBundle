<?php


namespace Teebb\CoreBundle\Listener;


use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Fields\SimpleValueItem;

class DynamicChangeFieldMetadataListener
{
    /**
     * @var FieldConfiguration
     */
    private $fieldConfiguration;
    /**
     * @var string
     */
    private $targetContentClassName;

    public function __construct(FieldConfiguration $fieldConfiguration, string $targetContentClassName)
    {
        $this->fieldConfiguration = $fieldConfiguration;
        $this->targetContentClassName = $targetContentClassName;
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        $classMetadata = $args->getClassMetadata();
        switch ($classMetadata->getName()) {
            case SimpleValueItem::class :
//                dd($this->fieldConfiguration, $this->targetContentClassName, $args);
                $fieldMapping = array(
                    'fieldName' => 'value',
                    'type' => 'string',
                    'nullable' => false
                );
                $classMetadata->mapField($fieldMapping);

                $classMetadata->setPrimaryTable(['name'=>'aaaaa']);
                break;
        }

    }

}