<?php


namespace Teebb\CoreBundle\Configuration;


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
     * 文件上传目录,可用变量 年：date.Y=2020 date.y=20 月：date.m=01 日：date.d=31
     * @var string
     */
    protected $uploadDir = "['date.Y']-['date.m']";

    /**
     * 最大文件上传大小
     * @var string
     */
    protected $maxSize;

    /**
     * 允许的最大图像尺寸，超出将裁剪 $maxResolution['height'], $maxResolution['width']
     * @array
     */
    protected $maxResolution;

    /**
     * 允许的最小图像尺寸，小于该尺寸将禁止上传
     * @array
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
     * @return array
     */
    public function getAllowExt(): array
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
     * @return string
     */
    public function getUploadDir(): string
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
     * @return string
     */
    public function getMaxSize(): string
    {
        return $this->maxSize;
    }

    /**
     * @param string $maxSize
     */
    public function setMaxSize(string $maxSize): void
    {
        $this->maxSize = $maxSize;
    }

    /**
     * @return mixed
     */
    public function getMaxResolution()
    {
        return $this->maxResolution;
    }

    /**
     * @param mixed $maxResolution
     */
    public function setMaxResolution($maxResolution): void
    {
        $this->maxResolution = $maxResolution;
    }

    /**
     * @return mixed
     */
    public function getMinResolution()
    {
        return $this->minResolution;
    }

    /**
     * @param mixed $minResolution
     */
    public function setMinResolution($minResolution): void
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