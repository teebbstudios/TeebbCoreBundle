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

namespace Teebb\CoreBundle\Tests\Functional\EntityType;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Teebb\CoreBundle\Translation\TranslatableMarkup;

class TranslationTest extends KernelTestCase
{
    /**
     * 测试Translation
     */
    public function testTransMarkup()
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();

        $translator = $container->get('teebb.core.twig.trans_markup_extension');

        $markup = new TranslatableMarkup('teebb.core.field.integer.label',[], 'TeebbCoreBundle');

        $result = $translator->translationMarkup($markup);

        $this->assertSame('整数', $result);
    }

}