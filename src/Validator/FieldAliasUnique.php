<?php


namespace Teebb\CoreBundle\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class FieldAliasUnique extends Constraint
{
    public $message = 'The value "{{ value }}" is already used.';
}