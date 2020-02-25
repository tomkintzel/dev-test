<?php

namespace Msq;

class UpdateHandler
{
    /**
     * @var string
     */
    private $featureName;
    /**
     * @var callable[]
     */
    private $updates;
    private $versions;

    /**
     * Erzeugt einen UpdateHandler für das Feature.<br>
     * Achtung: Die Updates sollten sortiert sein!<br>
     * Es sollte bevorzugt der {@link UpdateHandlerBuilder} benutzt werden.
     *
     * @param string $featureName Der Name des Features
     * @param array $updates Die Updates für die jeweiligen Versionen
     */
    public function __construct($featureName, array $updates)
    {
        $this->featureName = $featureName;
        $this->updates = $updates;
        $this->versions = array_keys($this->updates);
    }

    /**
     * @return UpdateHandlerBuilder Ein Builder, mit welchem Objekte dieser Klasser erzeugt werden können.
     */
    public static function builder()
    {
        return new UpdateHandlerBuilder();
    }

    /**
     * Verarbeitet ein UpdateEvent und gibt die neue Versionsnummer zurück-
     *
     * @param UpdateEvent $updateEvent Das auslösende UpdateEvent
     *
     * @return string Die neue Versionsnummer
     */
    public function handleUpdate(UpdateEvent $updateEvent)
    {
        $newVersion = $updateEvent->getToVersion();

        $fromIndex = array_search($updateEvent->getFromVersion(), $this->versions, true);
        $toIndex = array_search($updateEvent->getToVersion(), $this->versions, true);

        if ($fromIndex === false) {
            $fromIndex = 0;
        } else {
            $fromIndex++;
        }

        if ($toIndex === false) {
            $toIndex = 0;
        }

        for ($i = $fromIndex; $i <= $toIndex; $i++) {
            $this->updates[$this->versions[$i]]($updateEvent);
            $newVersion = $this->versions[$i];
        }

        return $newVersion;
    }

    /**
     * @return string Die aktuelle Version
     */
    public function getCurrentVersion()
    {
        end($this->updates);
        return key($this->updates);
    }

    /**
     * @return string
     */
    public function getFeatureName()
    {
        return $this->featureName;
    }

    /**
     * @return array
     */
    public function getUpdates()
    {
        return $this->updates;
    }
}
