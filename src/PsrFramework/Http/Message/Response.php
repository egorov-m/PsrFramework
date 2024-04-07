<?php

namespace Csu\PsrFramework\Http\Message;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;

class Response extends Message implements ResponseInterface
{
    protected static array $statusCode = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',

        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',

        451 => 'Unavailable For Legal Reasons',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required'
    ];

    protected int $status = 200;
    protected string $reasonPhrase = '';

    public function __construct(
        int $statusCode = 200,
        array $headers = [],
        $body = null,
        string $protocolVersion = '1.1',
        string $reasonPhrase = 'OK'
    ) {
        $this->assertStatusCode($statusCode);
        $this->assertProtocolVersion($protocolVersion);
        $this->assertReasonPhrase($reasonPhrase);

        parent::__construct($body);

        $this->setHeaders($headers);

        $this->status = $statusCode;
        $this->protocolVersion = $protocolVersion;
        $this->reasonPhrase = $reasonPhrase;
    }

    public function getStatusCode(): int
    {
        return $this->status;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $this->assertStatus($code);
        $this->assertReasonPhrase($reasonPhrase);

        $new = clone $this;
        $new->status = $code;
        if ($reasonPhrase === '' && isset(self::$statusCode[$code])) {
            $new->reasonPhrase = self::$statusCode[$code];
        } else {
            $new->reasonPhrase = $reasonPhrase;
        }

        return $new;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    private function assertStatus(int $code): void
    {
        if ($code < 100 || $code > 599) {
            throw new InvalidArgumentException(
                sprintf(
                    'Status code should be in a range of 100-599, but "%s" provided.',
                    $code
                )
            );
        }
    }

    private function assertReasonPhrase(string $reasonPhrase): void
    {
        if ($reasonPhrase === '') {
            return;
        }

        $escapePattern = '/[\x00-\x1F\x7F-\xFF]/';

        if (preg_match($escapePattern, $reasonPhrase)) {
            throw new InvalidArgumentException(
                'Reason phrase contains prohibited characters.'
            );
        }
    }

    private function assertStatusCode(int $statusCode): void
    {
        if ($statusCode < 100 || $statusCode >= 600) {
            throw new InvalidArgumentException(
                sprintf(
                    'Status code should be an integer value in the range of 100-599, but "%s" provided.',
                    $statusCode
                )
            );
        }
    }
}
