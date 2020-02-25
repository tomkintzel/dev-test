<?php

class MsqAction extends MsqHook
{

    public function register()
    {
        add_action(...$this->getRegistrationArguments());
    }
}
