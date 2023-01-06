<?php

namespace Gsdk\CronDatabase\Console;

use Gsdk\CronDatabase\Eloquent\Model;
use Gsdk\CronDatabase\Support\CronConsole;
use Illuminate\Console\Command;

class Job extends Command {

	protected $signature = 'cron:job
		{id}';

	protected $description = '';

	public function handle() {
		$job = Model::find($this->argument('id'));
		if (!$job)
			return $this->error('Cron job not found');
		//else if ($job->enabled)
		(new CronConsole())->run($job);
	}

}
