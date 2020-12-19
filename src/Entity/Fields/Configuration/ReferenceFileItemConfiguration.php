<?php


namespace Teebb\CoreBundle\Entity\Fields\Configuration;


class ReferenceFileItemConfiguration extends BaseItemConfiguration
{
    /**
     * 如果类型为 entity 则不处理doctrine metadata
     *
     * @var string
     */
    protected $type = 'entity';

    /**
     * 当编辑文件时是否添加显示，以控制文件的显示
     * @var bool
     */
    protected $showControl = true;

    /**
     * 允许的文件后缀
     *
     * @var array
     */
    protected $allowExt = ['txt'];

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
     * 是否使用文件描述，如果不使用则使用文件名
     * @var bool
     */
    protected $useDescription = true;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isShowControl(): bool
    {
        return $this->showControl;
    }

    /**
     * @param bool $showControl
     */
    public function setShowControl(bool $showControl): void
    {
        $this->showControl = $showControl;
    }

    /**
     * @return array|null
     */
    public function getAllowExt(): ?array
    {
        return $this->allowExt;
    }

    /**
     * @param array|null $allowExt
     */
    public function setAllowExt(?array $allowExt): void
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
     * @param string|null $uploadDir
     */
    public function setUploadDir(?string $uploadDir): void
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
     * @return bool
     */
    public function isUseDescription(): bool
    {
        return $this->useDescription;
    }

    /**
     * @param bool $useDescription
     */
    public function setUseDescription(bool $useDescription): void
    {
        $this->useDescription = $useDescription;
    }

}