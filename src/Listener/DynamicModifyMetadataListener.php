<?php


namespace Teebb\CoreBundle\Listener;


use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Teebb\CoreBundle\Event\ModifyClassMetadataEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;

class DynamicModifyMetadataListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $args)
    {
        if ($args instanceof ModifyClassMetadataEventArgs) {
            dd($args);
        }
    }

}