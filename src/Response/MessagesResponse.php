<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Response;

use TravisAMiller\SpotTrackerApi\Request\Filter;
use TravisAMiller\SpotTrackerApi\Request\MessagesRequest;

/**
 * Messages Feed Response
 *
 * Adds any desired functionality to the "latest" feed response object.
 */
class MessagesResponse extends AbstractResponse implements ResponseInterface
{
    /**
     * @var MessagesRequest
     */
    protected $request = null;

    /**
     * Number of messages included in each page.
     *
     * @var int
     */
    private $resultsPerPage = 50;

    /**
     * Determine if there is a page of results after this one.
     *
     * @return bool
     */
    public function hasNextPage(): bool
    {
        $nextStartPosition = $this->getCurrentPosition() + $this->resultsPerPage;

        return $nextStartPosition <= $this->getTotalMessageCount();
    }

    /**
     * Create request for next page of results.
     *
     * @return MessagesRequest
     */
    public function getNextPageRequest(): MessagesRequest
    {
        $filters = array_merge(
            $this->getRequestFilter()->toArray(),
            ['start' => $this->getCurrentPosition() + $this->resultsPerPage]
        );

        return new MessagesRequest(
            $this->request->getFeedId(),
            $this->request->getFeedPassword(),
            new Filter\MessagesFilter($filters)
        );
    }

    /**
     * Determine if there is page of results before this one.
     *
     * @return bool
     */
    public function hasPreviousPage(): bool
    {
        $nextStartPosition = $this->getCurrentPosition() - $this->resultsPerPage;

        return $nextStartPosition >= 0;
    }

    /**
     * Create request for previous page of results.
     *
     * @return MessagesRequest
     */
    public function getPreviousPageRequest(): MessagesRequest
    {
        $filters = array_merge(
            $this->getRequestFilter()->toArray(),
            ['start' => $this->getCurrentPosition() - $this->resultsPerPage]
        );

        return new MessagesRequest(
            $this->request->getFeedId(),
            $this->request->getFeedPassword(),
            new Filter\MessagesFilter($filters)
        );
    }

    /**
     * Get the filters for request used to generate these results.
     *
     * @return Filter\MessagesFilterInterface
     */
    private function getRequestFilter(): Filter\MessagesFilterInterface
    {
        /** @var Filter\MessagesFilterInterface $filter is enforced by constructor. */
        $filter = $this->request->getFilter();

        return $filter;
    }

    /**
     * Determine current position from request used to generate these results.
     *
     * @return int
     */
    private function getCurrentPosition(): int
    {
        $filter = $this->getRequestFilter();

        if ($filter->hasStartFilter()) {
            return $filter->getStartFilter();
        }

        return 0;
    }
}
