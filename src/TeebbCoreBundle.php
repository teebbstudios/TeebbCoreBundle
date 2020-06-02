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
use Teebb\CoreBundle\DependencyInjection\Compiler\EntityTypeCompilePass;
use Teebb\CoreBundle\DependencyInjection\Compiler\RouteLoaderCompilePass;

/**
 * Class TeebbCoreBundle
 */
class TeebbCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new EntityTypeCompilePass());
        $container->addCompilerPass(new RouteLoaderCompilePass());
    }
}