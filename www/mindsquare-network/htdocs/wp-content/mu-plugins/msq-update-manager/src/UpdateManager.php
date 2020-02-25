<?php

namespace Msq;

use function delete_option;
use function get_option;
use function update_option;

class UpdateManager
{
    const UPDATING_DB_KEY = 'updating';

    /** @var UpdateHandler[] */
    private $updateHandlers;
    private $dbPrefix;

    /**
     * Erstellt einen UpdateManager
     *
     * @param string $dbPrefix Das Präfix, welches für Einträge in der wp_options-Tabelle benutzt werden soll
     */
    public function __construct($dbPrefix)
    {
        $this->updateHandlers = [];
        $this->dbPrefix = $dbPrefix;

        add_action('wp_loaded', [$this, 'update']);
    }

    public function registerUpdateHandler(UpdateHandler $updateHandler)
    {
        if (!isset($updateHandler)) {
            return false;
        }

        $this->updateHandlers[$updateHandler->getFeatureName()] = $updateHandler;
        return true;
    }

    /**
     * Ruft die Update-Handler der registrierten Features auf.
     */
    public function update()
    {
        if (!$this->startUpdating()) {
            return;
        }

        try {
            $storedVersions = $this->readFromDatabase();
            $updatedVersions = [];

            foreach ($this->updateHandlers as $name => $handler) {
                $updateEvent = new UpdateEvent(
                    $name,
                    $storedVersions[$name],
                    $handler->getCurrentVersion()
                );

                $updatedVersion = $handler->handleUpdate($updateEvent);

                if ($updatedVersion !== $storedVersions[$name]) {
                    $updatedVersions[$name] = $updatedVersion;
                }
            }

            $this->writeToDatabase($updatedVersions);
        } finally {
            $this->stopUpdating();
        }
    }

    /**
     * Startet das Update.
     * @return bool Ob erfolgreich gestartet wurde, sprich kein Update bereits im Gange war.
     */
    protected function startUpdating()
    {
        $success = !$this->isUpdating();

         if ($success) {
             update_option($this->getUpdatingKey(), true);
         }

         return $success;
    }

    /**
     * Beendet das Update.
     * @return bool Ob erfolgreich beendet wurde, sprich ein Update im Gange war.
     */
    protected function stopUpdating()
    {
        $success = $this->isUpdating();

        if ($success) {
            delete_option($this->getUpdatingKey());
        }

        return $success;
    }

    /**
     * @return bool Ob gerade eine Aktualisierung durchgeführt wird.
     */
    public function isUpdating()
    {
        return get_option($this->getUpdatingKey()) !== false;
    }

    /**
     * @return string Der Schlüssel, mit welchem in der Datenbank gespeichert wird, dass gerade aktualisiert wird.
     */
    protected function getUpdatingKey()
    {
        return $this->dbPrefix . self::UPDATING_DB_KEY;
    }

    /**
     * Liest die Versionsnummern aus, die derzeit in der Datenbank hinterlegt sind.
     *
     * @return string[] Ein Array mit den Versionsnummern der Features
     */
    private function readFromDatabase()
    {
        $storedVersions = [];

        foreach ($this->updateHandlers as $name => $handler) {
            $dbKey = $this->getDatabaseKey($name);

            $storedVersions[$name] = get_option($dbKey, '0.0.0');
        }

        return $storedVersions;
    }

    /**
     * Schreibt die übergebenen Versionsnummern in die Datenbank.
     *
     * @param $versions
     *
     * @return bool Ob der Schreibevorgang erfolgreich war.
     */
    private function writeToDatabase($versions)
    {
        $success = true;

        foreach ($versions as $name => $version) {
            $dbKey = $this->getDatabaseKey($name);
            $success &= update_option($dbKey, $version);
        }

        return $success;
    }

    /**
     * @param string $featureName Der Name des Features
     *
     * @return string Der Schlüssel, mit welchem die Feature-Version in der Datenbank abgefragt werden kann.
     */
    private function getDatabaseKey($featureName)
    {
        return $this->dbPrefix.'_'.$featureName.'_version';
    }
}
