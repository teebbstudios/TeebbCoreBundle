<?php
/**
 * This file is part of the TeebbCoreBundle package.
 *
 * Author: Quan Weiwei <qww.zone@gmail.com>
 * Date: 2020/5/22
 * (c) teebbstudios
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Teebb\CoreBundle\Mapping;


/**
 * Gets reflection classes for php files in the given directories. Copy from api-platform.
 *
 * @author Antoine Bluchet <soyuka@gmail.com>
 *
 * @internal
 */
final class ReflectionClassRecursiveIterator
{
    public static function getReflectionClassesFromDirectories(array $directories): \Iterator
    {
        $directories = array_unique($directories);
        foreach ($directories as $path) {
            $iterator = new \RegexIterator(
                new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                ),
                '/^.+\.php$/i',
                \RecursiveRegexIterator::GET_MATCH
            );

            foreach ($iterator as $file) {
                $sourceFile = $file[0];

                if (!preg_match('(^phar:)i', $sourceFile)) {
                    $sourceFile = realpath($sourceFile);
                }

                require_once $sourceFile;

                $includedFiles[$sourceFile] = true;
            }
        }

        $declared = array_merge(get_declared_classes(), get_declared_interfaces());

        foreach ($declared as $className) {
            $reflectionClass = new \ReflectionClass($className);
            $sourceFile = $reflectionClass->getFileName();
            if (isset($includedFiles[$sourceFile])) {
                yield $className => $reflectionClass;
            }
        }
    }
}