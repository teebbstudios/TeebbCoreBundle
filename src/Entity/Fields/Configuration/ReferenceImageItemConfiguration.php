<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;


class ReferenceImageItemConfiguration extends BaseItemConfiguration
{
    /**
     * 如果类型为 entity 则不处理doctrine metadata
     *
     * @var string
     */
    protected $type = 'entity';

    /**
     * 允许的文件后缀
     *
     * @var array
     */
    protected $allowExt = ['jpg,jpeg,png'];

    /**
     * 文件上传目录,使用Date类 ExpressionLanguage
     * @var string
     */
    protected $uploadDir = '[date.Year~"-"~date.month~"-"~date.day]';

    /**
     * 最大文件上传大小
     * @var string|null
     */
    protected $maxSize = null;

    /**
     * 允许的最大图像尺寸，超出将裁剪 $maxResolution['height'], $maxResolution['width']
     * @var array
     */
    protected $maxResolution;

    /**
     * 允许的最小图像尺寸，小于该尺寸将禁止上传
     * @var array
     */
    protected $minResolution;

    /**
     * 图片使用alt属性
     *
     * @var bool
     */
    protected $useAlt = true;

    /**
     * Alt属性要求必填
     * @var bool
     */
    protected $altRequired = true;

    /**
     * 使用title属性
     * @var bool
     */
    protected $useTitle = false;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array|null
     */
    public function getAllowExt(): ?array
    {
        return $this->allowExt;
    }

    /**
     * @param array $allowExt
     */
    public function setAllowExt(array $allowExt): void
    {
        $this->allowExt = $allowExt;
    }

    /**
     * @return string|null
     */
    public function getUploadDir(): ?string
    {
        return $this->uploadDir;
    }

    /**
     * @param string $uploadDir
     */
    public function setUploadDir(string $uploadDir): void
    {
        $this->uploadDir = $uploadDir;
    }

    /**
     * @return string|null
     */
    public function getMaxSize(): ?string
    {
        return $this->maxSize;
    }

    /**
     * @param string|null $maxSize
     */
    public function setMaxSize(?string $maxSize): void
    {
        $this->maxSize = $maxSize;
    }

    /**
     * @return array|null
     */
    public function getMaxResolution(): ?array
    {
        return $this->maxResolution;
    }

    /**
     * @param array|null $maxResolution
     */
    public function setMaxResolution(?array $maxResolution): void
    {
        $this->maxResolution = $maxResolution;
    }

    /**
     * @return array|null
     */
    public function getMinResolution(): ?array
    {
        return $this->minResolution;
    }

    /**
     * @param array|null $minResolution
     */
    public function setMinResolution(?array $minResolution): void
    {
        $this->minResolution = $minResolution;
    }

    /**
     * @return bool
     */
    public function isUseAlt(): bool
    {
        return $this->useAlt;
    }

    /**
     * @param bool $useAlt
     */
    public function setUseAlt(bool $useAlt): void
    {
        $this->useAlt = $useAlt;
    }

    /**
     * @return bool
     */
    public function isAltRequired(): bool
    {
        return $this->altRequired;
    }

    /**
     * @param bool $altRequired
     */
    public function setAltRequired(bool $altRequired): void
    {
        $this->altRequired = $altRequired;
    }

    /**
     * @return bool
     */
    public function isUseTitle(): bool
    {
        return $this->useTitle;
    }

    /**
     * @param bool $useTitle
     */
    public function setUseTitle(bool $useTitle): void
    {
        $this->useTitle = $useTitle;
    }

}