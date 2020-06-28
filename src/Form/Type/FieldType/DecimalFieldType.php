<?php


namespace Teebb\CoreBundle\Form\Type\FieldType;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Repository\Fields\FieldConfigurationRepository;

class DecimalFieldType extends BaseFieldType
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var FieldConfigurationRepository
     */
    protected $fieldConfigurationsRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->fieldConfigurationsRepository = $entityManager->getRepository(FieldConfiguration::class);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        dd($options);
    }

    public function getParent()
    {
        return CheckboxType::class;
    }
}