<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Spot;

/**
 * Spot Location Message
 *
 * Provides details about a location/position message received from a Spot device.
 */
class Message
{
    /**
     * Spot provided message ID.
     *
     * @var int
     */
    private $id;

    /**
     * Unix timestamp when message was received.
     *
     * @var int
     */
    private $timestamp;

    /**
     * Type of message received.
     *
     * @var string
     */
    private $type;

    /**
     * Latitude component of the position.
     *
     * @var float
     */
    private $latitude;

    /**
     * Longitude component of the position.
     *
     * @var float
     */
    private $longitude;

    /**
     * Details about the device that sent the message.
     *
     * @var Messenger
     */
    private $messenger;

    /**
     * Message constructor.
     *
     * @param int $id
     * @param int $timestamp
     * @param string $type
     * @param float $latitude
     * @param float $longitude
     * @param Messenger $messenger
     */
    public function __construct(
        int $id,
        int $timestamp,
        string $type,
        float $latitude,
        float $longitude,
        Messenger $messenger
    ) {
        $this->id = $id;
        $this->timestamp = $timestamp;
        $this->type = $type;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->messenger = $messenger;
    }

    /**
     * Allows caller to easily create a Message object from json decoded with json_decode().
     *
     * @param \stdClass $object
     *
     * @return Message
     */
    public static function fromJsonObject(\stdClass $object)
    {
        return new static(
            $object->id,
            $object->unixTime,
            $object->messageType,
            $object->latitude,
            $object->longitude,
            new Messenger(
                $object->messengerId,
                $object->messengerName,
                $object->modelId
            )
        );
    }

    /**
     * Access to message ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Access to timestamp.
     *
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * Access to message type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Access to latitude.
     *
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * Access to longitude.
     *
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * Access to messenger details.
     *
     * @return Messenger
     */
    public function getMessenger(): Messenger
    {
        return $this->messenger;
    }
}
