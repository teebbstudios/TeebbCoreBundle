<?php


namespace Teebb\CoreBundle\Voter;


use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * 所有voter继承此类
 */
abstract class BaseVoter extends Voter implements TeebbVoterInterface
{

}