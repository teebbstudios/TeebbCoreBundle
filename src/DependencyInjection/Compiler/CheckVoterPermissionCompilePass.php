<?php


namespace Teebb\CoreBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Teebb\CoreBundle\Voter\TeebbVoterInterface;


/**
 * 获取所有TeebbVoterInterface实现类中的所有权限
 */
class CheckVoterPermissionCompilePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $voterOptionArray = [];
        foreach ($container->findTaggedServiceIds('teebb.voter') as $serviceId => $tags) {
            try {
                /**@var TeebbVoterInterface $voterService**/
                $voterService = $container->get($serviceId);
                $voterOptionArray[] = $voterService->getVoteOptionArray();
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }

        $container->setParameter('teebb.core.voter.permissions', $voterOptionArray);
    }
}