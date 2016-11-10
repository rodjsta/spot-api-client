<?php declare(strict_types=1);
// @codingStandardsIgnoreFile

/**
 * Use this file to make live requests to the API.
 */
require __DIR__ . '/../bootstrap/bootstrap.php';

/**
 * You will need a feedId with some recent messages.
 */
$feedId = getenv('SPOT_FEED_ID');

/**
 * Create API client instance.
 */
$client = new TravisAMiller\SpotTrackerApi\ApiClient($feedId);

/**
 * Fetch all available messages from the feed.
 */
$page = $client->messages();
do {
    if ($page->hasErrors()) {
        foreach($page->getErrors() as $error) {
            printf(
                "Error: %s (%s)",
                $error->getDescription(),
                $error->getCode()
            );
        }
        break;
    }

    foreach ($page->getMessages() as $message) {
        printf(
            "Location: %F %F (ID: %d)\n",
            $message->getLatitude(),
            $message->getLongitude(),
            $message->getId()
        );
    }
} while (
    $page->hasNextPage() &&
    $page = $client->send($page->getNextPageRequest())
);
