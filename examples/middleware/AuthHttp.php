<?php

namespace Csu\PsrFramework\Examples\middleware;

use ArrayAccess;
use Psr\Http\Message\ResponseFactoryInterface;

use InvalidArgumentException;


abstract class AuthHttp
{
    protected $users;
    protected $realm = 'Login';

    protected $attribute;

    protected $responseFactory;

    public function __construct($users, ResponseFactoryInterface $responseFactory = null)
    {
        if (!is_array($users) && !($users instanceof ArrayAccess)) {
            throw new InvalidArgumentException('The users argument must be an array or implement the ArrayAccess interface');
        }

        $this->users = $users;
    }

    public function realm(string $realm): self
    {
        $this->realm = $realm;

        return $this;
    }

    public function attribute(string $attribute): self
    {
        $this->attribute = $attribute;

        return $this;
    }
}