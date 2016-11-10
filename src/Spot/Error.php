<?php declare(strict_types = 1);

namespace TravisAMiller\SpotTrackerApi\Spot;

class Error
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $description;

    /**
     * Error constructor.
     *
     * @param string $code
     * @param string $text
     * @param string $description
     */
    public function __construct(string $code, string $text, string $description)
    {
        $this->code = $code;
        $this->text = $text;
        $this->description = $description;
    }

    public static function fromJsonObject(\stdClass $json)
    {
        return new static(
            $json->code ?? 'unknown',
            $json->text ?? 'unknown',
            $json->description ?? 'unknown'
        );
    }

    /**
     * Access API supplied error code.
     *
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Access API supplied short description/summary.
     *
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Access API supplied long description.
     *
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
}
