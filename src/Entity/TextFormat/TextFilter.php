<?php


namespace Teebb\CoreBundle\Entity\TextFormat;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * 文本过滤器
 *
 * @ORM\Entity
 * @Assert\EnableAutoMapping
 */
class TextFilter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string")
     */
    private $label;

    /**
     * 过滤器英文别名
     * @var string|null
     * @ORM\Column(type="string", unique=true)
     */
    private $alias;

    /**
     * 过滤器的一些值，比如：标签过滤器允许的标签值
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $filterValue;

    /**
     * 正则过滤器Pattern
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    private $filterPattern;

}