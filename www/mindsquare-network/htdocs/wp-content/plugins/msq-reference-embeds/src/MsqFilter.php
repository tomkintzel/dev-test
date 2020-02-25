<?php

class MsqFilter extends MsqHook
{
	public function register()
	{
		add_filter(...$this->getRegistrationArguments());
	}
}
