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

namespace Teebb\CoreBundle\Application;


use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Liip\ImagineBundle\LiipImagineBundle;
use Oneup\FlysystemBundle\OneupFlysystemBundle;
use Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;
use Teebb\CoreBundle\TeebbCoreBundle;

/**
 * 用于Teebb测试与版本号更新.
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public const VERSION = '0.1.8';

    public const VERSION_ID = '000108';

    public const MAJOR_VERSION = 0;

    public const MINOR_VERSION = 1;

    public const RELEASE_VERSION = 8;

    public const EXTRA_VERSION = '';

    public function registerBundles()
    {
        return [
            new TeebbCoreBundle(),
            new FrameworkBundle(),
            new TwigBundle(),
            new DoctrineBundle(),
            new StofDoctrineExtensionsBundle(),
            new OneupFlysystemBundle(),
            new LiipImagineBundle()
        ];
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {

    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $loader->load($c->getParameter('kernel.project_dir') . '/tests/Resources/config/config.yaml');
    }

}