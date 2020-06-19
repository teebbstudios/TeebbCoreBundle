<?php


namespace Teebb\CoreBundle\Validator;


use Symfony\Component\Validator\Constraint;

class FieldFileExtIsRightMime extends Constraint
{
    public $message = 'The ext "{{ value }}" is not right Mime type.';
}
