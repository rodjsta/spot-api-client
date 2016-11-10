<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Request;

use TravisAMiller\SpotTrackerApi\Request\Filter\FilterInterface;
use TravisAMiller\SpotTrackerApi\Request\Filter\MessagesFilter;

/**
 * Abstract Request
 *
 * Provides properties and generic functionality all requests depend on.
 */
abstract class AbstractRequest implements RequestInterface
{
    /**
     * Base URL that is used for all API requests.
     *
     * @var string
     */
    protected static $baseUrl = 'https://api.findmespot.com/spot-main-web/consumer/rest-api/2.0/public/feed';

    /**
     * Resource/endpoint being requested.
     *
     * This is customized at the Request object level.
     *
     * @var string
     */
    protected $resource = '';

    /**
     * Preferred response format.
     *
     * This library expects JSON encoded responses. The API can provide XML also.
     *
     * @var string
     */
    protected $format = 'json';

    /**
     * Unique Feed ID given created by Spot when a feed is published.
     *
     * Note: this generally given to you by the Spot device owner.
     *
     * @var string
     */
    protected $feedId = '';

    /**
     * (Optional) The feed password.
     *
     * Some feeds are password protected by their owner.
     *
     * @var string
     */
    protected $password = '';

    /**
     * (Optional) Filters to apply to the feed results.
     *
     * If no filters are supplied a NullFilter is inserted automatically.
     *
     * @var MessagesFilter
     */
    protected $filter = null;

    /**
     * Base Request constructor.
     *
     * @param string $feedId
     * @param string $password
     * @param FilterInterface|null $filter
     */
    public function __construct(string $feedId, string $password = '', Filter\FilterInterface $filter = null)
    {
        $this->feedId = $feedId;
        $this->password = $password;
        $this->filter = $filter ?? new Filter\NullFilter();
    }

    /**
     * {@inheritDoc}
     */
    public function getFeedId(): string
    {
        return $this->feedId;
    }

    /**
     * {@inheritDoc}
     */
    public function getFeedPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilter(): FilterInterface
    {
        return $this->filter;
    }

    /**
     * {@inheritDoc}
     */
    final public function getUrl() : string
    {
        return sprintf(
            '%s/%s/%s.%s?%s',
            self::$baseUrl,
            $this->feedId,
            $this->resource,
            $this->format,
            $this->buildQueryString()
        );
    }

    /**
     * Build query string for request and inject password if supplied.
     *
     * @return string
     */
    final private function buildQueryString(): string
    {
        $parameters = $this->filter->toArray();

        if ($this->password !== '') {
            $parameters['feedPassword'] = $this->password;
        }

        return http_build_query($parameters);
    }
}
