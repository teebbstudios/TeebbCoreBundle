<?php


namespace Teebb\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * 所有内容实体entity基类。Taxonomy、Comment、Content都继续此类
 *
 * @ORM\MappedSuperclass()
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class BaseContent
{
    use TimestampableEntity;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("main")
     */
    protected $id;

    /**
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}