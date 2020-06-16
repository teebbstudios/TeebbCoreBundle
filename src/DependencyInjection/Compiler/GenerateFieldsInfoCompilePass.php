<?php


namespace Teebb\CoreBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class GenerateFieldsInfoCompilePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $fieldInfo = [];
        foreach ($container->findTaggedServiceIds('teebb.field') as $serviceId => $tags) {
            foreach ($tags as $key => $value) {
                $fieldInfo[$value['type']][] = $serviceId;
            }
        }

        $addFieldsTypeDefinition = $container->getDefinition('teebb.core.form.add_fields_type');
        $addFieldsTypeDefinition->setArgument(0, $fieldInfo);

        //将所有字段Service Id按类型分组，并传递到EntityType Service
        foreach ($container->findTaggedServiceIds('teebb.entity_type') as $serviceId => $tags) {
            $definition = $container->getDefinition($serviceId);
            $definition->addMethodCall('setFieldList', [$fieldInfo]);
        }
    }
}