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

namespace Teebb\CoreBundle\AbstractService;


use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Metadata\FieldMetadataInterface;

/**
 * 所有字段Field类实现此接口
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
interface FieldInterface
{
    /**
     * 获取字段id
     * @return string
     * @throws \Exception
     */
    public function getFieldId(): string;

    /**
     * 设置字段Metadata
     * @param FieldMetadataInterface $metadata
     */
    public function setFieldMetadata(FieldMetadataInterface $metadata): void;

    /**
     * 获取字段Metadata
     * @return FieldMetadataInterface
     */
    public function getFieldMetadata(): FieldMetadataInterface;

    /**
     * 获取字段Entity类名
     * @return string
     */
    public function getFieldEntity(): string;

    /**
     * 获取字段设置表单Entity全类名
     * @return string
     */
    public function getFieldConfigFormEntity(): string;

    /**
     * 获取字段设置表单Type全类名
     * @return string
     */
    public function getFieldConfigFormType(): string;

    /**
     * 获取字段entity表单Type全类名
     * @return string
     */
    public function getFieldFormType(): string;

    /**
     * 获取内容Entity某字段数据
     * @param BaseContent $contentEntity 内容Entity
     * @param FieldConfiguration $fieldConfiguration 字段
     * @param string $targetEntityClassName 字段所属于的内容Entity类名
     * @param bool $flushCache 是否刷新缓存，用于修改内容时更新
     * @return array
     */
    public function getFieldEntityData(BaseContent $contentEntity, FieldConfiguration $fieldConfiguration,
                                       string $targetEntityClassName, bool $flushCache = false): array;

    /**
     * 把从数据库中读取到的表数据转为字段Entity对象
     * @param array $fieldRow
     * @param BaseContent $targetContentEntity
     * @return object
     */
    public function transformFieldRowToFieldEntity(array $fieldRow, BaseContent $targetContentEntity);
}