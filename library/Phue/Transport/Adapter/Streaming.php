<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Transport\Adapter;

/**
 * Streaming Http adapter
 */
class Streaming implements AdapterInterface
{
    /**
     * @var ?resource
     */
    protected $streamContext;

    /**
     * @var ?resource
     */
    protected $fileStream;

    /**
     * Opens the connection
     */
    public function open(): void
    {
        // Deliberately do nothing
    }

    /**
     * @inheritdoc
     */
    public function send(string $address, string $method, string $body = null): string|bool
    {
        // Init stream options
        $streamOptions = [
            'ignore_errors' => true,
            'method' => $method
        ];
        
        // Set body if there is one
        if (strlen($body)) {
            $streamOptions['content'] = $body;
        }
        
        $this->streamContext = stream_context_create(
            [
                'http' => $streamOptions
            ]
        );

        if(!$address)
        {
            return false;
        }

        // Make request
        $this->fileStream = @fopen($address, 'r', false, $this->streamContext);
        return $this->fileStream ? stream_get_contents($this->fileStream) : false;
    }

    /**
     * Get response http status code
     */
    public function getHttpStatusCode(): int
    {
        preg_match('#^HTTP/1\.1 (\d+)#mi', $this->getHeaders(), $matches);
        
        return $matches[1] ?? false;
    }

    /**
     * @inheritdoc
     */
    public function getContentType(): string
    {
        preg_match('#^Content-type: ([^;]+?)$#mi', $this->getHeaders(), $matches);
        
        return $matches[1] ?? false;
    }

    public function getHeaders(): string|null
    {
        // Don't continue if file stream isn't valid
        if (!$this->fileStream) {
            return null;
        }

        # https://www.php.net/manual/en/function.stream-get-meta-data.php
        $meta_data = stream_get_meta_data($this->fileStream);

        return implode(
            "\r\n",
            $meta_data['wrapper_data']
        );
    }

    /**
     * @inheritdoc
     */
    public function close(): void
    {
        if (is_resource($this->fileStream)) {
            fclose($this->fileStream);
        }
        
        $this->streamContext = null;
    }
}
