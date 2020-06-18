<?php


namespace Teebb\CoreBundle\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\MissingOptionsException;

/**
 * @deprecated
 * 字段列表键值对xxx|yyy,值yyy是否为正确格式float,integer,string
 * @Annotation
 */
class FieldListTypeRowValueRightFormat extends Constraint
{
    public $format;

    public $message = 'The row "{{ value }}" value is not "{{ type }}" format.';

    public function __construct($options = null)
    {
        if (null !== $options && !\is_array($options)) {
            $options = ['format' => $options];
        };

        parent::__construct($options);

        if (null === $this->format) {
            throw new MissingOptionsException(sprintf('Option "format" must be given for constraint "%s".', __CLASS__), ['format']);
        }
    }
}