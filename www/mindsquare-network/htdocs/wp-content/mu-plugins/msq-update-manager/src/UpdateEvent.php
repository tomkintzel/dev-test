<?php
namespace Msq;

class UpdateEvent
{
    /**
     * @var string Für welches Feature dieses Update bestimmt ist
     */
    private $feature;
    /**
     * @var string Von welcher Version aktualisiert wird
     */
    private $fromVersion;
    /**
     * @var string Auf welche Version aktualisiert wird
     */
    private $toVersion;

    /**
     * UpdateEvent constructor.
     *
     * @param string $feature
     * @param string $fromVersion
     * @param string $toVersion
     */
    public function __construct($feature, $fromVersion, $toVersion)
    {
        $this->feature = $feature;
        $this->fromVersion = $fromVersion;
        $this->toVersion = $toVersion;
    }


    /**
     * @return string
     */
    public function getFeature()
    {
        return $this->feature;
    }

    /**
     * @return string
     */
    public function getFromVersion()
    {
        return $this->fromVersion;
    }

    /**
     * @return string
     */
    public function getToVersion()
    {
        return $this->toVersion;
    }
}
