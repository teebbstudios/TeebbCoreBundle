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
    public function getFieldId():string;

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



//    /**
//     * 获取字段设置
//     *
//     * @param string $type 内容类型
//     * @param string $alias
//     * @param string $fieldName
//     * @return FieldConfiguration
//     */
//    public function getFieldConfiguration(string $type, string $alias, string $fieldName): FieldConfiguration;
}