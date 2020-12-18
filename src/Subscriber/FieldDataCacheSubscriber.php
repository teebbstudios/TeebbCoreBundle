<?php

namespace Teebb\CoreBundle\Subscriber;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Psr\Container\ContainerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Teebb\CoreBundle\Doctrine\DBAL\FieldDBALUtils;
use Teebb\CoreBundle\Event\DataCacheEvent;
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;
use Teebb\CoreBundle\Traits\GenerateNameTrait;

/**
 * 用于管理字段数据缓存
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class FieldDataCacheSubscriber implements EventSubscriberInterface
{
    use GenerateNameTrait;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var AdapterInterface
     */
    private $cacheAdapter;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var integer
     */
    private $expireAfter;

    public function __construct(ContainerInterface $container,
                                EntityManagerInterface $entityManager,
                                ParameterBagInterface $parameterBag,
                                AdapterInterface $cacheAdapter)
    {

        $this->entityManager = $entityManager;
        $this->cacheAdapter = $cacheAdapter;
        $this->container = $container;
        $this->expireAfter= $parameterBag->get('teebb.core.cache.expire_after');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DataCacheEvent::GET_FIELD_CACHE => 'getFieldDataCache',
            DataCacheEvent::DELETE_FIELD_CACHE => 'deleteFieldDataCache',
        ];
    }

    /**
     * @param DataCacheEvent $dataCacheEvent
     * @return mixed
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function getFieldDataCache(DataCacheEvent $dataCacheEvent)
    {
        $baseContent = $dataCacheEvent->getBaseContent();
        $field = $dataCacheEvent->getFieldConfiguration();
        $targetClassName = $dataCacheEvent->getTargetEntityClassName();
        $fieldService = $dataCacheEvent->getFieldService();

        $cacheKey = $this->generateCacheKey($baseContent, $field);

        return $this->cacheAdapter->get($cacheKey,
            function (ItemInterface $item) use ($baseContent, $field, $targetClassName, $fieldService) {
                $item->expiresAfter($this->expireAfter);

                $dynamicChangeFieldMetadataSubscriber = $this->container->get('teebb.core.event.dynamic_field_mapping_subscriber');

                $dynamicChangeFieldMetadataSubscriber->setFieldConfiguration($field);
                $dynamicChangeFieldMetadataSubscriber->setTargetContentClassName($targetClassName);

                $fieldDBALUtils = new FieldDBALUtils($this->entityManager, $field);

                $qb = $this->entityManager->getConnection()->createQueryBuilder();

                $conditions = [$qb->expr()->eq('entity_id', '?'), $qb->expr()->eq('types', '?')];
                $parameters = [$baseContent->getId(), $field->getTypeAlias()];
                $orderBy = ['delta' => 'ASC'];

                $fieldRows = $fieldDBALUtils->fetchFieldItem($qb, $conditions, $parameters, $orderBy);

                $fieldData = [];
                foreach ($fieldRows as $fieldRow) {
                    $fieldEntity = $fieldService->transformFieldRowToFieldEntity($fieldRow, $baseContent);
                    array_push($fieldData, $fieldEntity);
                }

                return $fieldData;
            });
    }

    /**
     * @param DataCacheEvent $cacheEvent
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function deleteFieldDataCache(DataCacheEvent $cacheEvent)
    {
        $needDeleteCacheKeyArray = $cacheEvent->getNeedDeleteCacheKeyArray();
        foreach ($needDeleteCacheKeyArray as $key) {
            $this->cacheAdapter->deleteItem($key);
        }
    }
}