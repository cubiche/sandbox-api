<?php

/**
 * This file is part of the Sandbox package.
 *
 * Copyright (c) Cubiche
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Behat\Service;

use GuzzleHttp\ClientInterface;

/**
 * WebApiService class.
 *
 * @author Ivan Suarez Jerez <ivannis.suarez@gmail.com>
 */
abstract class WebApiService
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @var SharedStorageInterface
     */
    protected $sharedStorage;

    /**
     * @var array
     */
    private $response;

    /**
     * @var array
     */
    private $errors;

    /**
     * @var string
     */
    protected $apiUrl = '/graphql';

    /**
     * @var array
     */
    protected $variables = array();
    protected $body;

    /**
     * WebApiService constructor.
     *
     * @param ClientInterface        $client
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(ClientInterface $client, SharedStorageInterface $sharedStorage)
    {
        $this->client = $client;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * Init the command/query.
     */
    public function init()
    {
        $this->headers = array();
        $this->variables = array();
        $this->response = null;
        $this->errors = null;

        $this->defaults();
    }

    /**
     * Set default variables.
     */
    protected function defaults()
    {
    }

    /**
     * @param string $variable
     * @param mixed  $value
     */
    protected function set($variable, $value)
    {
        $this->variables[$variable] = $value;
    }

    /**
     * Send the command/query request.
     */
    protected function send()
    {
        $this->addAuthorization();

        $response = $this->client->post(
            $this->apiUrl,
            array(
                'json' => array(
                    'query' => $this->getQuery(),
                    'variables' => $this->variables,
                ),
                'headers' => $this->headers,
            )
        );

        $responseBody = json_decode($response->getBody(), true);
        if ($responseBody === null) {
            throw new \RuntimeException('Invalid api call: '.json_encode($this->getQuery()));
        }

        $this->body = $responseBody;
        if (isset($responseBody['errors'])) {
            $this->errors = $responseBody['errors'];
        } else {
            $this->response = $responseBody['data'];
        }
    }

    /**
     * Add authorization header.
     */
    protected function addAuthorization()
    {
        if ($this->sharedStorage->has('jwt')) {
            $jwt = $this->sharedStorage->get('jwt');

            if ($jwt !== null) {
                $this->addHeader('Authorization', 'Bearer '.$jwt);
            }
        }
    }

    /**
     * Adds header.
     *
     * @param string $name
     * @param string $value
     */
    protected function addHeader($name, $value)
    {
        if (isset($this->headers[$name])) {
            if (!is_array($this->headers[$name])) {
                $this->headers[$name] = array($this->headers[$name]);
            }
            $this->headers[$name][] = $value;
        } else {
            $this->headers[$name] = $value;
        }
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function getResponse($key)
    {
        if ($this->response !== null) {
            if (isset($this->response[$key])) {
                return $this->response[$key];
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return bool
     */
    protected function hasErrors()
    {
        return $this->errors !== null;
    }

    /**
     * @param string $statusCode
     *
     * @return bool
     */
    public function hasErrorWithCode($statusCode)
    {
        if ($this->hasErrors()) {
            foreach ($this->errors as $error) {
                if ($error['code'] === $statusCode) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $string
     *
     * @return bool
     */
    public function hasValidationErrorWith($string)
    {
        if ($this->hasErrors()) {
            foreach ($this->errors as $error) {
                if ($error['code'] !== 422) {
                    continue;
                }

                if (is_array($error['errors'])) {
                    foreach ($error['errors'] as $fieldError) {
                        if ($string === $fieldError['message']) {
                            return true;
                        }
                    }
                } else {
                    if ($string === $error['message']) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasValidationErrorKey($key)
    {
        if ($this->hasErrors()) {
            foreach ($this->errors as $error) {
                if ($error['code'] !== 422) {
                    continue;
                }

                if (is_array($error['errors'])) {
                    foreach ($error['errors'] as $fieldError) {
                        if ($key === $fieldError['path']) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /**
     * @return string
     */
    abstract protected function getQuery();
}
