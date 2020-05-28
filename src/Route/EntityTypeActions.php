<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/25
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Route;

/**
 * 定义内容实体类型类EntityType通用操作
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
final class EntityTypeActions
{
    /**
     * 显示类型信息
     */
    public const SHOW = 'show';

    /**
     * 列表显示所有类型
     */
    public const INDEX = 'index';

    /**
     * 创建实体类型
     */
    public const CREATE = 'create';

    /**
     * 更新实体类型
     */
    public const UPDATE = 'update';

    /**
     * 删除实体类型
     */
    public const DELETE = 'delete';

    /**
     * 显示实体类型所有字段
     */
    public const INDEX_FIELD = 'index_field';

    /**
     * 添加字段
     */
    public const ADD_FIELD = 'add_field';

    /**
     * 编辑字段
     */
    public const UPDATE_FIELD = 'update_field';

    /**
     * 设置字段
     */
    public const SETTING_FIELD = 'setting_field';

    /**
     * 删除字段
     */
    public const DELETE_FIELD = 'delete_field';
}