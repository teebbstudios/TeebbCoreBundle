<?php


namespace Teebb\CoreBundle\Controller;


use Doctrine\DBAL\ConnectionException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Teebb\CoreBundle\AbstractService\FieldInterface;
use Teebb\CoreBundle\Doctrine\DBAL\FieldDBALUtils;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Comment;
use Teebb\CoreBundle\Entity\Fields\BaseFieldItem;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Event\DataCacheEvent;
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;
use Teebb\CoreBundle\Traits\GenerateNameTrait;

/**
 * 内容及字段数据DBAL存储与删除Trait
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
trait SubstanceDBALOptionsTrait
{
    use GenerateNameTrait;

    /**
     * 持久化内容及所有字段数据
     * @param EntityManagerInterface $entityManager
     * @param FieldConfigurationRepository $fieldConfigRepository
     * @param EventDispatcherInterface $eventDispatcher 保存内容时发送消息清理字段缓存
     * @param FormInterface $form
     * @param string $bundle 用于排序显示所有字段
     * @param string $typeAlias 内容类型的别名，用于获取当前内容类型的所有字段
     * @param string $contentClassName 内容Entity全类名，用于动态修改字段映射
     * @return mixed
     * @throws ConnectionException
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    protected function persistSubstance(EntityManagerInterface $entityManager,
                                        FieldConfigurationRepository $fieldConfigRepository,
                                        EventDispatcherInterface $eventDispatcher,
                                        FormInterface $form, string $bundle, string $typeAlias,
                                        string $contentClassName)
    {
        //内容Entity object
        $substance = $form->getData();

        //获取当前内容类型所有字段
        $fields = $fieldConfigRepository
            ->getBySortableGroupsQueryDesc(['bundle' => $bundle, 'typeAlias' => $typeAlias])->getResult();

        $cacheKeyArray = [];

        //doctrine Event manager
        $evm = $entityManager->getEventManager();

        $conn = $entityManager->getConnection();
        $conn->beginTransaction();
        try {
            $entityManager->persist($substance);
            $entityManager->flush();

            /**@var FieldConfiguration $field * */
            foreach ($fields as $field) {
                $fieldCacheKey = $this->generateCacheKey($substance, $field);
                array_push($cacheKeyArray, $fieldCacheKey);
                //获取当前字段的所有表单数据
                $fieldDataArray = $form->get($field->getFieldAlias())->getData();

                if (!empty($fieldDataArray)) {
                    //动态修改字段entity的mapping
                    $dynamicChangeFieldMetadataListener = new DynamicChangeFieldMetadataListener($field, $contentClassName);
                    $evm->addEventListener(Events::loadClassMetadata, $dynamicChangeFieldMetadataListener);

                    /**@var BaseFieldItem $fieldItem * */
                    foreach ($fieldDataArray as $index => $fieldItem) {
                        //处理字段和内容Entity的关系
                        $fieldItem->setEntity($substance);

                        $fieldDBALUtils = new FieldDBALUtils($entityManager, $field);

                        $fieldDBALUtils->persistFieldItem($fieldItem);
                    }
                    //移除doctrine监听器
                    $evm->removeEventListener(Events::loadClassMetadata, $dynamicChangeFieldMetadataListener);
                }
            }

            $conn->commit();
        } catch (\Exception $exception) {
            $conn->rollBack();
            throw $exception;
        }

        //将要删除缓存Key的数组，发送给delete.field.cache消息
        $dataCacheEvent = new DataCacheEvent();
        $dataCacheEvent->setNeedDeleteCacheKeyArray($cacheKeyArray);
        $eventDispatcher->dispatch($dataCacheEvent, DataCacheEvent::DELETE_FIELD_CACHE);

        return $substance;
    }


    /**
     * 删除内容及其字段数据
     *
     * @param EntityManagerInterface $entityManager
     * @param FieldConfigurationRepository $fieldConfigRepository
     * @param ContainerInterface $container
     * @param string $bundle
     * @param string $typeAlias
     * @param BaseContent $data
     * @throws ConnectionException
     * @throws \Exception
     */
    protected function deleteSubstance(EntityManagerInterface $entityManager, FieldConfigurationRepository $fieldConfigRepository,
                                       ContainerInterface $container, string $bundle, string $typeAlias, BaseContent $data)
    {
        //获取当前内容类型所有字段
        $fields = $fieldConfigRepository
            ->getBySortableGroupsQueryDesc(['bundle' => $bundle, 'typeAlias' => $typeAlias])
            ->getResult();

        $conn = $entityManager->getConnection();

        $conn->beginTransaction();
        try {
            /**@var FieldConfiguration $field * */
            foreach ($fields as $field) {
                /**@var FieldInterface $fieldService * */
                $fieldService = $container->get('teebb.core.field.' . $field->getFieldType());

                $fieldDBALUtils = new FieldDBALUtils($entityManager, $field);

                $fieldItems = $fieldService->getFieldEntityData($data, $field, get_class($data));

                foreach ($fieldItems as $fieldItem) {
                    $fieldDBALUtils->deleteFieldItem($fieldItem);
                }
            }

            if ($data instanceof Taxonomy) {
                $taxonomyRepo = $entityManager->getRepository(Taxonomy::class);
                $taxonomyRepo->removeFromTree($data);
            } elseif ($data instanceof Comment) {
                $commentRepo = $entityManager->getRepository(Comment::class);
                $commentRepo->removeFromTree($data);
            } else {
                $entityManager->remove($data);
            }
            $entityManager->flush();

            $conn->commit();
        } catch (\Exception $e) {
            $conn->rollBack();
            throw $e;
        }

    }

}