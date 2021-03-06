<?php


namespace Teebb\CoreBundle\Form;

/**
 * 获取Annotation中表单行配置
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class FormRowMarkup
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var bool
     */
    private $isAlias;

    /**
     * @var string
     */
    private $formType;

    /**
     * @var array|null
     */
    private $options;

    public function __construct(string $property,bool $isAlias, string $formType, ?array $options)
    {
        $this->property = $property;
        $this->isAlias = $isAlias;
        $this->formType = $formType;
        $this->options = $options;
    }

    /**
     * @return string
     */
    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @return bool
     */
    public function isAlias(): bool
    {
        return $this->isAlias;
    }

    /**
     * @return string
     */
    public function getFormType(): string
    {
        return $this->formType;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options ?? [];
    }

}