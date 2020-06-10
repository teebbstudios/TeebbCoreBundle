<?php


namespace Teebb\CoreBundle\Tests\Functional\Templating;


use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Teebb\CoreBundle\Templating\TemplateRegistry;

class TemplateRegistryTest extends KernelTestCase
{
    /**
     * @var TemplateRegistry
     */
    private $registry;

    protected function setUp()
    {
        self::bootKernel();

        $this->registry = self::$container->get('teebb.core.template.registry');
    }

    public function testSetExtraLibraries()
    {
        $extra = $this->registry->getExtraLibraries();

        $this->assertCount(2, $extra);

        foreach ($extra as $key => $value)
        {
            $this->registry->setExtraLibraries([$key => $value['css_path']]);
            $this->registry->setExtraJavaScripts([$key => $value['js_path']]);
        }

        $this->assertNotNull($this->registry->getStylesheets()['extra']);
        $this->assertNotNull($this->registry->getJavascripts()['extra']);

    }

    public function testSetExtraLibrariesWithLibName()
    {
        $extra = $this->registry->getExtraLibraries();

        $this->assertCount(2, $extra);

        $this->registry->addExtraToStyleSheetsAndJavaScripts(['extra1', 'extra2']);

        $this->assertNotEmpty($this->registry->getJavascripts()['extra']);
        $this->assertNotEmpty($this->registry->getStylesheets()['extra']);

        $styleSheets = $this->registry->getStylesheets();
        $js = $this->registry->getJavascripts();
        $this->assertArrayHasKey('extra1',$styleSheets['extra']);
        $this->assertArrayHasKey('extra1',$js['extra']);

        $this->assertArrayHasKey('extra2',$styleSheets['extra']);
        $this->assertArrayHasKey('extra2',$js['extra']);

        $this->assertContains('path/to/extra1.min.css', $this->registry->getAllStyleSheets());
        $this->assertContains('path/to/extra1.min.js', $this->registry->getAllJavaScripts());
    }
}