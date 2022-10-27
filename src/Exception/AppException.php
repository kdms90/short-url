<?php

namespace App\Exception;

use Exception as baseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AppException
 * @package App\Exception
 */
class AppException extends baseException
{
    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;

    /**
     * AppException constructor.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    public function __construct(Response $response)
    {
        parent::__construct();
        $this->response = $response;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return $this
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }
}