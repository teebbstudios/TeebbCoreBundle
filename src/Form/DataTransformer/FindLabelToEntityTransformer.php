<?php


namespace Teebb\CoreBundle\Form\DataTransformer;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

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

    public function __construct(EntityManagerInterface $entityManager, string $entityClass, string $findLabel, string $typesLabel = null, array $referenceTypes = [])
    {
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
        $this->findLabel = $findLabel;
        $this->referenceTypes = $referenceTypes;
        $this->typesLabel = $typesLabel;
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

        if ($this->typesLabel){
            $criteria[$this->typesLabel] = $this->referenceTypes;
        }

        $entity = $entityRepo->findOneBy($criteria);

        if (!$entity) {
            throw new TransformationFailedException(sprintf('Not found "%s". Maybe the input form value error.', $value));
        }

        return $entity;
    }
}