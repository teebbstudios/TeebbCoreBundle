<?php


namespace Teebb\CoreBundle\Voter;

/**
 * 所有Voter实现此接口，用于系统自动发现及设置权限
 */
interface TeebbVoterInterface
{
    /**
     * 获取所有vote操作数组，[操作描述=>操作名称]，示例: [teebb.core.voter.edit.description => option]
     * @return array
     */
    public function getVoteOptionArray(): array;
}