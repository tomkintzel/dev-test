<?php

namespace Msq;

use function version_compare;

class UpdateHandlerBuilder
{
    /**
     * @var string
     */
    private $featureName;
    /**
     * @var callable[]
     */
    private $updates;

    /**
     * @return UpdateHandler Ein UpdateHandler für das angegebene Feature, mit automatisch sortierten Updates.
     */
    public function build()
    {
        /**
         * Updates automatisch nach Versionsnummer sortieren, sodass die neusten Updates die höchsten Indizes haben.
         */
        uksort($this->updates, 'version_compare');
        return new UpdateHandler(
            $this->featureName,
            $this->updates
        );
    }

    /**
     * @param string $featureName
     *
     * @return UpdateHandlerBuilder
     */
    public function withFeatureName($featureName)
    {
        $this->featureName = $featureName;
        return $this;
    }

    /**
     * @param string   $version
     * @param callable $update
     *
     * @return UpdateHandlerBuilder
     */
    public function addUpdate($version, callable $update)
    {
        $this->updates[$version] = $update;
        return $this;
    }
}
