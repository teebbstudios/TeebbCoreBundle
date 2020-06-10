<?php


namespace Teebb\CoreBundle\Templating;


class TemplateRegistry implements TemplateRegistryInterface
{
    /**
     * @var array
     */
    private $templates;

    /**
     * @var array
     */
    private $stylesheets;

    /**
     * @var array
     */
    private $javascripts;

    /**
     * 不同页面需要添加的所有额外assets库
     *
     * @var array
     */
    private $extraLibraries;

    public function __construct(array $templates, array $stylesheets, array $javascripts, array $extraLibraries)
    {
        $this->templates = $templates;
        $this->stylesheets = $stylesheets;
        $this->javascripts = $javascripts;
        $this->extraLibraries = $extraLibraries;
    }

    /**
     * @inheritDoc
     */
    public function setExtraStyleSheets(array $extra): void
    {
        foreach ($extra as $key => $value) {
            $this->stylesheets['extra'][$key] = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function setExtraJavaScripts(array $extra): void
    {
        foreach ($extra as $key => $value) {
            $this->javascripts['extra'][$key] = $value;
        }

    }

    /**
     * @inheritDoc
     */
    public function getTemplate(string $name, string $category = null): string
    {
        if (null !== $category) {
            if (!isset($this->templates[$category][$name])) {
                throw new \InvalidArgumentException(sprintf('Template named "%s" under category "%s" doesn\'t exist.', $name, $category));
            }
            return $this->templates[$category][$name];
        }

        if (!isset($this->templates[$name])) {
            throw new \InvalidArgumentException(sprintf('Template named "%s" doesn\'t exist.', $name));
        }
        return $this->templates[$name];
    }

    /**
     * @inheritDoc
     */
    public function getAllTemplates(): array
    {
        return $this->templates;
    }

    /**
     * @inheritDoc
     */
    public function getAllStyleSheets(): array
    {
        return $this->getAssets($this->stylesheets);
    }

    /**
     * @inheritDoc
     */
    public function getAllJavaScripts(): array
    {
        return $this->getAssets($this->javascripts);
    }

    /**
     * @inheritDoc
     */
    public function getStylesheets(): array
    {
        return $this->stylesheets;
    }

    /**
     * @inheritDoc
     */
    public function getJavascripts(): array
    {
        return $this->javascripts;
    }

    /**
     * @inheritDoc
     */
    public function getExtraLibraries(): array
    {
        return $this->extraLibraries;
    }

    /**
     * @inheritDoc
     */
    public function setExtraLibraries(array $extraLibraries): void
    {
        $this->extraLibraries = $extraLibraries;
    }

    /**
     * @inheritDoc
     */
    public function getExtraLibrary(string $name): array
    {
        if (!isset($this->extraLibraries[$name])) {
            throw new \InvalidArgumentException(sprintf('Extra library named "%s" doesn\'t exist.', $name));
        }
        return $this->extraLibraries[$name];
    }

    /**
     * @inheritDoc
     */
    public function addExtraToStyleSheetsAndJavaScripts(array $extraNames): void
    {
        foreach ($extraNames as $extraName) {
            $extraLib = $this->getExtraLibrary($extraName);

            if (isset($extraLib['css_path'])) {
                $this->setExtraStyleSheets([$extraName => $extraLib['css_path']]);
            }
            if (isset($extraLib['js_path'])) {
                $this->setExtraJavaScripts([$extraName => $extraLib['js_path']]);
            }
        }
    }


    /**
     * 按序输出所有Assets
     * @param array $assets
     * @return array
     */
    private function getAssets(array $assets): array
    {
        $allAssets = [];
        foreach ($assets as $name => $asset) {
            if ('extra' === $name) {
                foreach ($asset as $extra_key => $extra_value) {
                    $allAssets[] = $extra_value;
                }
            } else {
                $allAssets[] = $asset;
            }
        }
        return $allAssets;
    }
}