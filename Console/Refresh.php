<?php

namespace Gsdk\CronDatabase\Console;

use Gsdk\CronDatabase\Support\CrontabUpdater;
use Illuminate\Console\Command;

class Refresh extends Command {

	protected $signature = 'cron:refresh';

	protected $description = '';

	public function handle() {
		(new CrontabUpdater())->update();
	}

}
