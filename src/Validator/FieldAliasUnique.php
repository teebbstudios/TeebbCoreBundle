<?php


namespace Teebb\CoreBundle\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * 字段别名唯一约束
 *
 * @Annotation
 */
class FieldAliasUnique extends Constraint
{
    public $message = 'The value "{{ value }}" is already used.';
}