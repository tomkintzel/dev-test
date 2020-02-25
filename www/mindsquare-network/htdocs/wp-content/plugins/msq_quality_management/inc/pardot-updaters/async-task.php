<?php
namespace MSQ\Plugin\Quality_Management\Pardot_Updaters;

abstract class Async_Task {
	/** @var int STATUS_WAITING */
	public const STATUS_WAITING = 0x00;

	/** @var int STATUS_RUNNING */
	public const STATUS_RUNNING = 0x01;

	/** @var int STATUS_DONE */
	public const STATUS_DONE = 0x02;

	/** @param mixed $arg */
	protected abstract function execute( $arg );
}
