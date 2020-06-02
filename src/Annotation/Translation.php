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

use Doctrine\Common\Annotations\Annotation\Required;
use Teebb\CoreBundle\Translation\TranslatableMarkup;

/**
 * I18N.
 *
 * @Annotation
 * @Target({"ALL"})
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Translation
{
    /**
     * @var string
     * @Required()
     */
    public $message;

    /**
     * @var array
     */
    public $arguments = [];

    /**
     * @var string Translation domain
     */
    public $domain = 'TeebbCoreBundle';

    /**
     * @var TranslatableMarkup
     */
    private $translatable;

    public function __construct(array $values)
    {
        $this->message = $values['message'];
        $this->arguments = isset($values['arguments']) ? $values['arguments'] : [];
        $this->domain = isset($values['domain']) ? $values['domain'] : '';

        $this->translatable = new TranslatableMarkup($this->message, $this->arguments, $this->domain);
    }

    public function get()
    {
        return $this->translatable;
    }
}