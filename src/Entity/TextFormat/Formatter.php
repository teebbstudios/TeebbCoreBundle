<?php


namespace Teebb\CoreBundle\Entity\TextFormat;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * 格式化器Entity
 *
 * @ORM\Entity
 * @Assert\EnableAutoMapping
 */
class Formatter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    /**
     * Todo: 用户组
     * @var array|null
     * @ORM\Column(type="string", unique=true)
     */
    private $roles;

    /**
     * 编辑器toolbar配置名称
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $editorToolbar;

    /**
     * 格式化器的过滤设置
     * @var array|null
     */
    private $formatterSettings;
}