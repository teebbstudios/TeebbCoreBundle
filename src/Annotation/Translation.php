<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/21
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Annotation;

/**
 * I18N.
 *
 * @Annotation
 * @Target({"ALL"})
 * @Attributes(
 *     @Attribute("message", type="string"),
 *     @Attribute("domain", type="string")
 * )
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Translation
{
    /**
     * @var string
     */
    public $message;

    /**
     * @var string Translation domain
     */
    public $domain;

}