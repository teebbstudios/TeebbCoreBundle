<?php


namespace Teebb\CoreBundle\Translation;


class TranslatableMarkup
{
    /**
     * @var string
     */
    private $message;
    /**
     * @var array
     */
    private $arguments;
    /**
     * @var string
     */
    private $domain;

    public function __construct(string $message, array $arguments, string $domain)
    {
        $this->message = $message;
        $this->arguments = $arguments;
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

}