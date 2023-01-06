<?php

namespace Gsdk\CronDatabase\Support;

use Gsdk\CronDatabase\Eloquent\Model;

class CronConsole {

	const STATUS_OK = 1;
	const STATUS_FAILED = 2;
	const STATUS_PROCESSING = 3;

	public function runBackground(Model $cron): void {
		/*$locale = 'ru_RU.UTF-8';
		setlocale(LC_ALL, $locale);
		putenv('LC_ALL=' . $locale);*/

		$command = $cron->toConsoleJob();
		//dd($command);
		$command .= ' > /dev/null 2>/dev/null';
		$command .= ' & echo $!';
		$pid = shell_exec($command);
		$cron->update([
			//'pid' => $pid,
			'last_status' => self::STATUS_PROCESSING,
			//'last_executed' => null,
			'last_log' => null
		]);
	}

	public function run(Model $cron): void {
		ignore_user_abort(true);
		set_time_limit(0);
		$cron->update([
			'last_status' => self::STATUS_PROCESSING,
			'last_log' => null
		]);
		/*$locale = 'ru_RU.UTF-8';
		setlocale(LC_ALL, $locale);
		putenv('LC_ALL=' . $locale);*/
		$command = $cron->toConsoleCommand();
		$response = shell_exec($command) ?: null;

		$cron->update([
			'last_executed' => now(),
			'last_status' => $response === null ? self::STATUS_OK : self::STATUS_FAILED,
			'last_log' => trim($response)
		]);
	}
}