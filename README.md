# Spot Tracker API Client

A PHP client for the Spot Tracker Shared Page API.

## About the API

Though it is not overly publicized there is a free, public data feed available for Spot Trackers available
providing detailed tracking data for each device for the last 7 days.

Relatively sparse documentation is available here: [http://faq.findmespot.com/index.php?action=showEntry&data=69](http://faq.findmespot.com/index.php?action=showEntry&data=69)

The information provided by this API is the same information one would see on their Spot Shared Page.

Data returned from this API is encoded as either XML or JSON depending on the request URI.

Some feeds are password protected and the caller must pass a matches password in order to retrieve data.

## API Request Types

The API supports two types of feeds: **latest** and **messages**.

### Latest Message Request

Returns the most recent (single) position data available from a feed. As the result of this call is a single message there is no filtering or pagination of results.

This is most useful if just need to know at a given time where a device is currently located.


```php
use TravisAMiller\SpotTrackerApi\ApiClient;

$client = new ApiClient('your-feed-id-here');

$result = $client->latest();

$result->getFeed();     // Details about data feed.
$result->getMessages(); // Array of all messages received -- only one for this endpoint.
$result->getMessage();  // Direct accesss to message recevied from this endpoint.
```

### Message Feed Request

Returns a list of messages based on (optional) criteria provided by the caller. Messages are automatically paginated into result sets of 50 by the remote server.

Additionally, some filters can be applied to the messages feed.

```php
use TravisAMiller\SpotTrackerApi\ApiClient;

$client = new ApiClient('your-feed-id-here');

// retrieve messages on the first page of results, any date range.
$result = $client->messages();

// retrieve messages on the second page of results.
$result = $client->messages([
    'start' => 50
]);

// only retrieve messages newer than midnight yesterday.
$result = $client->messages([
    'startDate' => new DateTime('yesterday midnight')
]);

// only retrieve messages since older than midnight today.
$result = $client->messages([
    'endDate' => new DateTime('today midnight')
]);

// retrieve message from yesterday on the third page.
$result = $client->messages([
    'start' => 100, // 0 = first age, 50 = second page, 100 = third page
    'startDate' => new DateTime('yesterday midnight'),
    'endDate' => new DateTime('today midnight')
]);
```

The response contains some methods to make pagination easier.

```php
use TravisAMiller\SpotTrackerApi\ApiClient;

$client = new ApiClient('your-feed-id-here');

$result = $client->messages([
    'start' => 50
]);

$result->hasNextPage(); // true if there are more than 100 results.
$result->hasPreviousPage() // true if there the current position isn't the first page.

$request = $result->getNextPageRequest(); // get request for next page of results.
$request = $result->getPreviousPageRequest(); // get request for last page of results.

$result = $client->send($request); // gets results for either request above.
```

The following code will retrieve all available messages from the API:

```php
use TravisAMiller\SpotTrackerApi\ApiClient;

$client = new ApiClient('your-feed-id-here');

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
```

# Custom Request Filters

In addition to providing request filters as an array:

```php
$result = $client->messages([
    'start' => 50
]);
```

It is also possible to pass a request filter directly:

```php
$yesterday = new MessagesFilter([
    'startDate' => new DateTime("yesterday midnight"),
    'endDate' => new DateTime("today midnight"),
]);

$results = $client->messages($yesterday);
```

 This allows for the re-use of a filter across many requests.  
 
