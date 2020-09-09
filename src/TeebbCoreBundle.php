<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/20
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle;


use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Teebb\CoreBundle\DependencyInjection\Compiler\CheckVoterPermissionCompilePass;
use Teebb\CoreBundle\DependencyInjection\Compiler\GenerateFieldsInfoCompilePass;
use Teebb\CoreBundle\DependencyInjection\Compiler\GlobalVariablesCompilePass;
use Teebb\CoreBundle\DependencyInjection\Compiler\RegisterServicesCompilePass;
use Teebb\CoreBundle\DependencyInjection\Compiler\RouteLoaderCompilePass;
use Teebb\CoreBundle\DependencyInjection\Compiler\TwigFormThemesCompilePass;

/**
 * Class TeebbCoreBundle
 */
class TeebbCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RegisterServicesCompilePass());
        $container->addCompilerPass(new RouteLoaderCompilePass());
        $container->addCompilerPass(new GenerateFieldsInfoCompilePass());
        $container->addCompilerPass(new GlobalVariablesCompilePass());
        $container->addCompilerPass(new TwigFormThemesCompilePass());
        $container->addCompilerPass(new CheckVoterPermissionCompilePass(),PassConfig::TYPE_OPTIMIZE);
    }
}