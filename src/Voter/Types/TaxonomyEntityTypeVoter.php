<?php


namespace Teebb\CoreBundle\Voter\Types;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * 分类类型Voter
 */
class TaxonomyEntityTypeVoter extends AbstractTypesVoter
{
    public const TAXONOMY_ENTITY_TYPE_INDEX = 'taxonomy_entity_type_index';
    public const TAXONOMY_ENTITY_TYPE_NEW = 'taxonomy_entity_type_new';
    public const TAXONOMY_ENTITY_TYPE_UPDATE = 'taxonomy_entity_type_update';
    public const TAXONOMY_ENTITY_TYPE_DELETE = 'taxonomy_entity_type_delete';
    public const TAXONOMY_ENTITY_TYPE_INDEX_FIELDS = 'taxonomy_entity_type_index_fields';
    public const TAXONOMY_ENTITY_TYPE_ADD_FIELD = 'taxonomy_entity_type_add_field';
    public const TAXONOMY_ENTITY_TYPE_UPDATE_FIELD = 'taxonomy_entity_type_update_field';
    public const TAXONOMY_ENTITY_TYPE_DELETE_FIELD = 'taxonomy_entity_type_delete_field';
    public const TAXONOMY_ENTITY_TYPE_DISPLAY_FIELDS = 'taxonomy_entity_type_display_fields';

    /**
     * 获取当前voter所有权限
     * @return array
     */
    public function getVoteOptionArray(): array
    {
        return [
            'teebb.core.voter.taxonomy_entity_type_index' => self::TAXONOMY_ENTITY_TYPE_INDEX,
            'teebb.core.voter.taxonomy_entity_type_new' => self::TAXONOMY_ENTITY_TYPE_NEW,
            'teebb.core.voter.taxonomy_entity_type_update' => self::TAXONOMY_ENTITY_TYPE_UPDATE,
            'teebb.core.voter.taxonomy_entity_type_delete' => self::TAXONOMY_ENTITY_TYPE_DELETE,
            'teebb.core.voter.taxonomy_entity_type_index_fields' => self::TAXONOMY_ENTITY_TYPE_INDEX_FIELDS,
            'teebb.core.voter.taxonomy_entity_type_add_field' => self::TAXONOMY_ENTITY_TYPE_ADD_FIELD,
            'teebb.core.voter.taxonomy_entity_type_update_field' => self::TAXONOMY_ENTITY_TYPE_UPDATE_FIELD,
            'teebb.core.voter.taxonomy_entity_type_delete_field' => self::TAXONOMY_ENTITY_TYPE_DELETE_FIELD,
            'teebb.core.voter.taxonomy_entity_type_display_fields' => self::TAXONOMY_ENTITY_TYPE_DISPLAY_FIELDS,
        ];
    }

    protected function supports(string $attribute, $subject)
    {
        // TODO: Implement supports() method.
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        // TODO: Implement voteOnAttribute() method.
    }

}