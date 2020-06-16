<?php


namespace Teebb\CoreBundle\Validator;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;

class FieldAliasUniqueValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint FieldAliasUnique */

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new \UnexpectedValueException($value, 'string');
        }

        $fieldConfigurationRepo = $this->entityManager->getRepository(FieldConfiguration::class);
        $fieldConfigurations = $fieldConfigurationRepo->findBy(['fieldAlias' => $value]);

        if (!empty($fieldConfigurations)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}