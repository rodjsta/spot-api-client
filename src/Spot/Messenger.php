<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Spot;

/**
 * Spot Device Details
 *
 * Provides information about the Spot device that used to send a location/position message.
 */
class Messenger
{
    /**
     * Spot Device ID.
     *
     * @var string
     */
    private $id;

    /**
     * Owner assigned device name.
     *
     * @var string
     */
    private $name;

    /**
     * Owner assigned device description.
     *
     * @var string
     */
    private $model;

    /**
     * Messenger constructor.
     *
     * @param string $id
     * @param string $name
     * @param string $model
     */
    public function __construct(string $id, string $name, string $model)
    {
        $this->id = $id;
        $this->name = $name;
        $this->model = $model;
    }

    /**
     * Access to device ID.
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Access to device name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Access to device model.
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }
}
