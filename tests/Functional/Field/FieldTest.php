<?php


namespace Teebb\CoreBundle\Tests\Functional\Field;


use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Teebb\CoreBundle\Entity\Content;
use Teebb\CoreBundle\Entity\Fields\Configuration\DatetimeItemConfiguration;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;

class FieldTest extends KernelTestCase
{
    public function testGetFieldEntityData()
    {
        $kernel = self::bootKernel();

        $container = $kernel->getContainer();

        $fieldService = $container->get('teebb.core.field.boolean');
        $fieldConfiguration = new FieldConfiguration();

        $fieldConfiguration->setBundle('content');
        $fieldConfiguration->setTypeAlias('article');
        $fieldConfiguration->setFieldLabel('日期');
        $fieldConfiguration->setFieldType('datetime');
        $fieldConfiguration->setFieldAlias('ri_qi');

        $fieldSettings = new DatetimeItemConfiguration();
        $fieldConfiguration->setSettings($fieldSettings);

        $this->expectException(TableNotFoundException::class);
        $data = $fieldService->getFieldEntityData(20, $fieldConfiguration, Content::class);

    }
}