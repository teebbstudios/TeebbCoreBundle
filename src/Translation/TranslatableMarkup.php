<?php


namespace Teebb\CoreBundle\Translation;


class TranslatableMarkup
{
    /**
     * @var string
     */
    private $messages;
    /**
     * @var array
     */
    private $arguments;
    /**
     * @var string
     */
    private $domain;

    public function __construct(string $messages, array $arguments, string $domain)
    {
        $this->messages = $messages;
        $this->arguments = $arguments;
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getMessages(): string
    {
        return $this->messages;
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