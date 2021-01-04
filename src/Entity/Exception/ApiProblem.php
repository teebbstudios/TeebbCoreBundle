<?php


namespace Teebb\CoreBundle\Entity\Exception;


class ApiProblem
{
    const TYPE_VALIDATION_ERROR = 'validation_error';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';
    const TYPE_INVALID_REQUEST_PARAMETERS = 'invalid_request_parameters';
    const TYPE_PERMISSION_DENY = 'permission_deny';

    private static $titles = array(
        self::TYPE_VALIDATION_ERROR => 'There was a validation error',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent',
        self::TYPE_INVALID_REQUEST_PARAMETERS => 'Invalid request parameters sent',
        self::TYPE_PERMISSION_DENY => 'Permission deny',
    );

    private $type;
    private $title;
    private $extraData = array();

    private $statusCode;

    public function __construct($statusCode, $type)
    {
        $this->statusCode = $statusCode;
        $this->type = $type;
        if (!isset(self::$titles[$type])) {
            throw new \InvalidArgumentException('No title for type ' . $type);
        }
        $this->title = self::$titles[$type];
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode): void
    {
        $this->statusCode = $statusCode;
    }

    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
    }

    public function toArray()
    {
        return array_merge(
            $this->extraData,
            array(
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
            )
        );
    }
}