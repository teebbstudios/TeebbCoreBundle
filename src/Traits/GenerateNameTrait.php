<?php


namespace Teebb\CoreBundle\Traits;


use Teebb\CoreBundle\Entity\BaseContent;
use Teebb\CoreBundle\Entity\Fields\FieldConfiguration;

trait GenerateNameTrait
{
    public function generateCacheKey(BaseContent $baseContent, FieldConfiguration $field): string
    {
        return $field->getBundle() . '_' . $baseContent->getId() . '_' . $field->getFieldAlias() . '_' . $field->getFieldType();
    }
}