<?= "<?php\n" ?>


namespace <?= $namespace ?>;

use Doctrine\ORM\Mapping as ORM;

/**
 * 在数据库里仅用一个column存储字段的值
 *
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\Fields\FieldRepository")
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class <?= $class_name ?> extends BaseFieldItem
{
    /**
     * 动态映射
     * @var mixed
     */
    private $value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

}