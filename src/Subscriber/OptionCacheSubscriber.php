<?php

namespace Teebb\CoreBundle\Subscriber;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Teebb\CoreBundle\Doctrine\DBAL\FieldDBALUtils;
use Teebb\CoreBundle\Event\DataCacheEvent;
use Teebb\CoreBundle\Event\MenuCacheEvent;
use Teebb\CoreBundle\Event\OptionCacheEvent;
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;
use Teebb\CoreBundle\Traits\GenerateNameTrait;

/**
 * 用于管理设置缓存
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class OptionCacheSubscriber implements EventSubscriberInterface
{
    /**
     * @var AdapterInterface
     */
    private $cacheAdapter;
    /**
     * @var integer
     */
    private $expireAfter;

    public function __construct(AdapterInterface $cacheAdapter, ParameterBagInterface $parameterBag)
    {
        $this->cacheAdapter = $cacheAdapter;

        $this->expireAfter= $parameterBag->get('teebb.core.cache.expire_after');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            OptionCacheEvent::DELETE_OPTION_CACHE => 'deleteOptionCache',
        ];
    }

    /**
     * @param OptionCacheEvent $cacheEvent
     * @throws InvalidArgumentException
     */
    public function deleteOptionCache(OptionCacheEvent $cacheEvent)
    {
        $optionName = $cacheEvent->getOptionName();

        $this->cacheAdapter->deleteItem($optionName);
    }
}