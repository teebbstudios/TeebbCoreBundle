<?php


namespace Teebb\CoreBundle\Twig;


use Teebb\CoreBundle\Application\Kernel;

class GlobalVariables
{
    /**
     * @var string
     */
    private $version;

    public function __construct()
    {
        $this->version = Kernel::VERSION;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

}