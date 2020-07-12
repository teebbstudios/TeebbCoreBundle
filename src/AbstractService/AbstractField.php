<?php


namespace Teebb\CoreBundle\AbstractService;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;
use Teebb\CoreBundle\Listener\DynamicChangeFieldMetadataListener;
use Teebb\CoreBundle\Metadata\FieldMetadataInterface;
use Teebb\CoreBundle\Repository\Fields\FieldRepository;

/**
 * Class AbstractField
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
abstract class AbstractField implements FieldInterface
{
    /**
     * @var FieldMetadataInterface
     */
    protected $metadata;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function getFieldId(): string
    {
        if (null == $this->metadata) {
            throw new \Exception(sprintf('The field service "%s" $metadata did not set.', self::class));
        }
        return $this->metadata->getId();
    }

    /**
     * @inheritDoc
     */
    public function setFieldMetadata(FieldMetadataInterface $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @inheritDoc
     */
    public function getFieldMetadata(): FieldMetadataInterface
    {
        return $this->metadata;
    }

    /**
     * 获取字段Entity类名
     * @return string
     */
    public function getFieldEntity(): string
    {
        return $this->metadata->getEntity();
    }

    /**
     * 获取字段设置表单Entity全类名
     * @return string
     */
    public function getFieldConfigFormEntity(): string
    {
        return $this->metadata->getFieldFormConfigEntity();
    }

    /**
     * 获取字段设置表单Type全类名
     * @return string
     */
    public function getFieldConfigFormType(): string
    {
        return $this->metadata->getFieldFormConfigType();
    }

    /**
     * @inheritDoc
     */
    public function getFieldFormType(): string
    {
        return $this->metadata->getFieldFormType();
    }

    /**
     * @inheritDoc
     */
    public function getFieldEntityData(BaseContent $contentEntity, FieldConfiguration $fieldConfiguration, string $targetEntityClassName): array
    {
        $evm = $this->entityManager->getEventManager();
        $dynamicChangeMetadataListener = new DynamicChangeFieldMetadataListener($fieldConfiguration, $targetEntityClassName);
        $evm->addEventListener(Events::loadClassMetadata, $dynamicChangeMetadataListener);

        $fieldEntityClassName = $this->getFieldEntity();

        /**@var FieldRepository $fieldEntityRepository * */
        $fieldEntityRepository = $this->entityManager->getRepository($fieldEntityClassName);

        $fieldData = $fieldEntityRepository->findBy(['entity' => $contentEntity, 'types' => $fieldConfiguration->getTypeAlias()], ['delta' => 'ASC']);

        $evm->removeEventListener(Events::loadClassMetadata, $dynamicChangeMetadataListener);

        return $fieldData;
    }
}