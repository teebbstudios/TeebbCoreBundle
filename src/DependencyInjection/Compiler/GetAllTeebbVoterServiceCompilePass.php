<?php


namespace Teebb\CoreBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * 获取所有TeebbVoterInterface Service,并将所有Service Id写入Parameter
 */
class GetAllTeebbVoterServiceCompilePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $voterServiceArray = [];
        foreach ($container->findTaggedServiceIds('teebb.voter') as $serviceId => $tags) {
            $voterServiceArray[] = $serviceId;
        }

        $container->setParameter('teebb.core.voter.services', $voterServiceArray);
    }
}