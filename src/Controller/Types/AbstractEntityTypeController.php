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

namespace Teebb\CoreBundle\Controller\Types;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Teebb\CoreBundle\AbstractService\EntityTypeInterface;

/**
 * 内容实体类型EntityType的Controller
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class AbstractEntityTypeController extends AbstractController
{
    /**
     * @var EntityTypeInterface
     */
    protected $entityTypeService;

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

    /**
     * 读取request参数_teebb_entity_type获取EntityTypeService
     *
     * @internal
     * @required
     */
    public function configure()
    {
        $request = $this->getRequest();

        $entityTypeServiceId = $request->get('_teebb_entity_type');

        if (!$entityTypeServiceId) {
            throw new \RuntimeException(sprintf(
                'There is no `_teebb_entity_type` defined for the controller `%s` and the current route `%s`',
                static::class,
                $request->get('_route')
            ));
        }

        $this->entityTypeService = $this->container->get($entityTypeServiceId);

        if (!$this->entityTypeService) {
            throw new \RuntimeException(sprintf(
                'Unable to find the entity type service class related to the current controller (%s)',
                static::class
            ));
        }
    }

    /**
     * 显示不同内容实体类型EntityType列表
     *
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        dd($request);
    }
}