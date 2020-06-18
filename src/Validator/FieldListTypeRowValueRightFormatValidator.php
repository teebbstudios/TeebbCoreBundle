<?php


namespace Teebb\CoreBundle\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @deprecated
 */
class FieldListTypeRowValueRightFormatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint FieldListTypeRowValueRightFormat */

        if (null === $value || '' === $value) {
            return;
        }

        $format = $constraint->format;

        $checkFail = false;

        $errorResults = [];
        foreach ($value as $rowKey => $rowValue) {
            if (!$this->checkFormat($format, $rowValue)) {
                $checkFail = true;
                $errorResults[] = $rowKey . '|' . $rowValue;
            }
        }

        if ($checkFail) {

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', implode(',', $errorResults))
                ->setParameter('{{ type }}', $format)
                ->addViolation();
        }
    }

    private function checkFormat(string $format, $value): bool
    {

        $result = false;
        switch ($format) {
            case "float":
                $result = floatval($value);
                break;
            case "integer":
                $result = intval($value);
                break;
        }
        return $result;
    }
}