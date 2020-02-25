<?php

abstract class MsqHook
{
    protected $hook;
    protected $component;
    protected $callback;
    protected $priority;
    protected $acceptedArgs;

    /**
     * MsqHook constructor.
     *
     * @param $hook
     * @param $component
     * @param $callback
     * @param $priority
     * @param $acceptedArgs
     */
    public function __construct(
        $hook,
        $component,
        $callback,
        $priority = 10,
        $acceptedArgs = 1
    ) {
        $this->hook         = $hook;
        $this->component    = $component;
        $this->callback     = $callback;
        $this->priority     = $priority;
        $this->acceptedArgs = $acceptedArgs;
    }


    abstract public function register();

    public function getRegistrationArguments()
    {
        return [
            $this->hook,
            [ $this->component, $this->callback ],
            $this->priority,
            $this->acceptedArgs
        ];
    }
}
