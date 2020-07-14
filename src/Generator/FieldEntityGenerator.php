<?php


namespace Teebb\CoreBundle\Generator;


use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;

class FieldEntityGenerator
{
    /**
     * @var Generator
     */
    private $generator;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(Generator $generator, ContainerInterface $container)
    {
        $this->generator = $generator;
        $this->container = $container;
    }

    public function generateFieldEntity(FieldConfiguration $fieldConfiguration)
    {

    }
}