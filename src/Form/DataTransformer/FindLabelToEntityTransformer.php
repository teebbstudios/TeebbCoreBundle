<?php


namespace Teebb\CoreBundle\Form\DataTransformer;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Teebb\CoreBundle\Entity\Taxonomy;
use Teebb\CoreBundle\Entity\Types\Types;

/**
 * 引用实体字段表单label转对象
 */
class FindLabelToEntityTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var string
     */
    private $findLabel;

    /**
     * @var array
     */
    private $referenceTypes;
    /**
     * @var string
     */
    private $typesLabel;

    /**
     * @var Types|null
     */
    private $autoCreateToType;

    public function __construct(EntityManagerInterface $entityManager, string $entityClass,
                                string $findLabel, string $typesLabel = null, array $referenceTypes = [],
                                Types $autoCreateToType = null)
    {
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
        $this->findLabel = $findLabel;
        $this->referenceTypes = $referenceTypes;
        $this->typesLabel = $typesLabel;
        $this->autoCreateToType = $autoCreateToType;
    }

    //object to string
    public function transform($value)
    {
        if (null === $value) {
            return '';
        }

        if (!$value instanceof $this->entityClass) {
            throw new \LogicException(sprintf('The form value is not instanceof "%s" objects', $this->entityClass));
        }

        $theGetLabelMethodName = 'get' . ucfirst($this->findLabel);

        if (!method_exists($value, $theGetLabelMethodName)) {
            throw new \RuntimeException(sprintf('The "%s" class must define "%s" method or the option "find_label" error.',
                get_class($value), $theGetLabelMethodName));
        }

        return $value->{$theGetLabelMethodName}();
    }

    //string to object
    public function reverseTransform($value)
    {
        if (!$value) {
            return;
        }

        $entityRepo = $this->entityManager->getRepository($this->entityClass);

        $criteria = [$this->findLabel => $value];

        if ($this->typesLabel) {
            $criteria[$this->typesLabel] = $this->referenceTypes;
        }

        $entity = $entityRepo->findOneBy($criteria);

        //如果是引用分类字段，且当前value不存大对应的分类词汇，则添加新的词汇到对应分类
        if ($this->entityClass === Taxonomy::class && !$entity) {
            $entity = new Taxonomy();
            $entity->setTerm($value);
            $entity->setTaxonomyType($this->autoCreateToType->getTypeAlias());
            $entity->setDescription($value);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

        } elseif (!$entity) {
            throw new TransformationFailedException(sprintf('Not found "%s". Maybe the input form value error.', $value));
        }

        return $entity;
    }
}