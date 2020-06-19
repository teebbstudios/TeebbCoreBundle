<?php


namespace Teebb\CoreBundle\Validator;


use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * 验证用户设置的文件后缀是否为正确后缀
 */
class FieldFileExtIsRightMimeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint FieldFileExtIsRightMime */
        if (null === $value || '' === $value) {
            return;
        }
        $mimeTypes = new MimeTypes();

        $errorExtArray = [];
        foreach ($value as $ext) {
            $result = $mimeTypes->getMimeTypes($ext);
            if (empty($result)) {
                $errorExtArray[] = $ext;
            }
        }

        if (!empty($errorExtArray)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', implode(' ', $errorExtArray))
                ->addViolation();
        }
    }
}