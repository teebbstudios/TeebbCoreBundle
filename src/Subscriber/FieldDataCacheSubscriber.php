<?php

namespace Teebb\CoreBundle\Subscriber;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Symfony\Component\Cache\Adapter\AdapterInterface;
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

    public function __construct(EntityManagerInterface $entityManager, AdapterInterface $cacheAdapter)
    {

        $this->entityManager = $entityManager;
        $this->cacheAdapter = $cacheAdapter;
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

                $evm = $this->entityManager->getEventManager();
                $dynamicChangeMetadataListener = new DynamicChangeFieldMetadataListener($field, $targetClassName);
                $evm->addEventListener(Events::loadClassMetadata, $dynamicChangeMetadataListener);

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

                $evm->removeEventListener(Events::loadClassMetadata, $dynamicChangeMetadataListener);

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
        foreach ($needDeleteCacheKeyArray as $key)
        {
            $this->cacheAdapter->deleteItem($key);
        }
    }
}