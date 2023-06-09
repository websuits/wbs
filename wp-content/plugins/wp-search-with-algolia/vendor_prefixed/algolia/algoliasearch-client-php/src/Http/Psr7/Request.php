<?php
/**
 * @license MIT
 *
 * Modified by WebDevStudios on 23-February-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace WebDevStudios\WPSWA\Algolia\AlgoliaSearch\Http\Psr7;

use InvalidArgumentException;
use WebDevStudios\WPSWA\Psr\Http\Message\RequestInterface;
use WebDevStudios\WPSWA\Psr\Http\Message\StreamInterface;
use WebDevStudios\WPSWA\Psr\Http\Message\UriInterface;

/**
 * PSR-7 request implementation.
 *
 * @internal
 */
class Request implements RequestInterface
{
    /** @var string */
    private $method;

    /** @var string|null */
    private $requestTarget;

    /** @var UriInterface */
    private $uri;

    /** @var array Map of all registered headers, as original name => array of values */
    private $headers = [];

    /** @var array Map of lowercase header name => original name at registration */
    private $headerNames = [];

    /** @var string */
    private $protocol = '1.1';

    /** @var StreamInterface */
    private $stream;

    /**
     * @param string                               $method  HTTP method
     * @param string|UriInterface                  $uri     URI
     * @param array                                $headers Request headers
     * @param string|resource|StreamInterface|null $body    Request body
     * @param string                               $version Protocol version
     */
    public function __construct(
        $method,
        $uri,
        array $headers = [],
        $body = null,
        $version = '1.1'
    ) {
        if (!($uri instanceof UriInterface)) {
            $uri = new Uri($uri);
        }

        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->setHeaders($headers);
        $this->protocol = $version;

        if (!$this->hasHeader('Host')) {
            $this->updateHostFromUri();
        }

        if ('' !== $body && null !== $body) {
            $this->stream = stream_for($body);
        }
    }

    /**
     * @return string|null
     */
    public function getRequestTarget()
    {
        if (null !== $this->requestTarget) {
            return $this->requestTarget;
        }

        $target = $this->uri->getPath();
        if ('' == $target) {
            $target = '/';
        }
        if ('' != $this->uri->getQuery()) {
            $target .= '?'.$this->uri->getQuery();
        }

        return $target;
    }

    /**
     * @return Request
     */
    public function withRequestTarget($requestTarget)
    {
        if (preg_match('#\s#', $requestTarget)) {
            throw new InvalidArgumentException('Invalid request target provided; cannot contain whitespace');
        }

        $new = clone $this;
        $new->requestTarget = $requestTarget;

        return $new;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return Request
     */
    public function withMethod($method)
    {
        $new = clone $this;
        $new->method = strtoupper($method);

        return $new;
    }

    /**
     * @return Uri|UriInterface|string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @return Request
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        if ($uri === $this->uri) {
            return $this;
        }

        $new = clone $this;
        $new->uri = $uri;

        if (!$preserveHost) {
            $new->updateHostFromUri();
        }

        return $new;
    }

    /**
     * @return void
     */
    private function updateHostFromUri()
    {
        $host = $this->uri->getHost();

        if ('' == $host) {
            return;
        }

        if (null !== ($port = $this->uri->getPort())) {
            $host .= ':'.$port;
        }

        if (isset($this->headerNames['host'])) {
            $header = $this->headerNames['host'];
        } else {
            $header = 'Host';
            $this->headerNames['host'] = 'Host';
        }
        // Ensure Host is the first header.
        // See: http://tools.ietf.org/html/rfc7230#section-5.4
        $this->headers = [$header => [$host]] + $this->headers;
    }

    /**
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocol;
    }

    /**
     * @return Request
     */
    public function withProtocolVersion($version)
    {
        if ($this->protocol === $version) {
            return $this;
        }
        $new = clone $this;
        $new->protocol = $version;

        return $new;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return bool
     */
    public function hasHeader($header)
    {
        return isset($this->headerNames[strtolower($header)]);
    }

    /**
     * @return array|mixed
     */
    public function getHeader($header)
    {
        $header = strtolower($header);
        if (!isset($this->headerNames[$header])) {
            return [];
        }
        $header = $this->headerNames[$header];

        return $this->headers[$header];
    }

    /**
     * @return string
     */
    public function getHeaderLine($header)
    {
        return implode(', ', $this->getHeader($header));
    }

    /**
     * @return Request
     */
    public function withHeader($header, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $value = $this->trimHeaderValues($value);
        $normalized = strtolower($header);
        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            unset($new->headers[$new->headerNames[$normalized]]);
        }
        $new->headerNames[$normalized] = $header;
        $new->headers[$header] = $value;

        return $new;
    }

    /**
     * @return Request
     */
    public function withAddedHeader($header, $value)
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $value = $this->trimHeaderValues($value);
        $normalized = strtolower($header);
        $new = clone $this;
        if (isset($new->headerNames[$normalized])) {
            $header = $this->headerNames[$normalized];
            $new->headers[$header] = array_merge($this->headers[$header], $value);
        } else {
            $new->headerNames[$normalized] = $header;
            $new->headers[$header] = $value;
        }

        return $new;
    }

    /**
     * @return Request
     */
    public function withoutHeader($header)
    {
        $normalized = strtolower($header);
        if (!isset($this->headerNames[$normalized])) {
            return $this;
        }
        $header = $this->headerNames[$normalized];
        $new = clone $this;
        unset($new->headers[$header], $new->headerNames[$normalized]);

        return $new;
    }

    /**
     * @return PumpStream|Stream|StreamInterface
     */
    public function getBody()
    {
        if (!$this->stream) {
            $this->stream = stream_for('');
        }

        return $this->stream;
    }

    /**
     * @return Request
     */
    public function withBody(StreamInterface $body)
    {
        if ($body === $this->stream) {
            return $this;
        }
        $new = clone $this;
        $new->stream = $body;

        return $new;
    }

    /**
     * @return void
     */
    private function setHeaders(array $headers)
    {
        $this->headerNames = $this->headers = [];
        foreach ($headers as $header => $value) {
            if (!is_array($value)) {
                $value = [$value];
            }
            $value = $this->trimHeaderValues($value);
            $normalized = strtolower($header);
            if (isset($this->headerNames[$normalized])) {
                $header = $this->headerNames[$normalized];
                $this->headers[$header] = array_merge($this->headers[$header], $value);
            } else {
                $this->headerNames[$normalized] = $header;
                $this->headers[$header] = $value;
            }
        }
    }

    /**
     * Trims whitespace from the header values.
     *
     * Spaces and tabs ought to be excluded by parsers when extracting the field value from a header field.
     *
     * header-field = field-name ":" OWS field-value OWS
     * OWS          = *( SP / HTAB )
     *
     * @param string[] $values Header values
     *
     * @return string[] Trimmed header values
     *
     * @see https://tools.ietf.org/html/rfc7230#section-3.2.4
     */
    private function trimHeaderValues(array $values)
    {
        return array_map(function ($value) {
            return trim($value, " \t");
        }, $values);
    }
}
