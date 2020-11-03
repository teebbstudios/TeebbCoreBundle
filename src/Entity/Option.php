<?php


namespace Teebb\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Teebb\CoreBundle\Entity\Options\OptionInterface;

/**
 * 系统基本设置类
 * @ORM\Entity
 * @ORM\Table(name="options")
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Option
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 设置的英文名称
     * @var string|null
     * @ORM\Column(type="string", unique=true)
     */
    private $optionName;

    /**
     * 用于存储设置信息
     * @var OptionInterface|null
     * @ORM\Column(type="object", nullable=true)
     */
    private $optionValue;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getOptionName(): ?string
    {
        return $this->optionName;
    }

    /**
     * @param string|null $optionName
     */
    public function setOptionName(?string $optionName): void
    {
        $this->optionName = $optionName;
    }

    /**
     * @return OptionInterface|null
     */
    public function getOptionValue(): ?OptionInterface
    {
        return $this->optionValue;
    }

    /**
     * @param OptionInterface|null $optionValue
     */
    public function setOptionValue(?OptionInterface $optionValue): void
    {
        $this->optionValue = $optionValue;
    }

}