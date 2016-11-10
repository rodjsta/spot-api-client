<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Spot;

/**
 * Spot Feed Details
 *
 * Provides information about the Spot feed provided by the Spot device owner.
 */
class Feed
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $days;

    /**
     * Feed constructor.
     *
     * @param string $id
     * @param string $name
     * @param string $description
     * @param int $days
     */
    public function __construct(string $id, string $name, string $description, int $days)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->days = $days;
    }

    public static function fromJsonObject(\stdClass $object) : Feed
    {
        return new static(
            $object->id,
            $object->name,
            $object->description,
            $object->daysRange
        );
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getDays(): int
    {
        return $this->days;
    }
}
