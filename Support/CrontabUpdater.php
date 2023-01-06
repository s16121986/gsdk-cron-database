<?php

namespace Gsdk\CronDatabase\Support;

use Gsdk\CronDatabase\Helpers;
use Gsdk\CronDatabase\Eloquent\Model;

class CrontabUpdater {
	const TAB_CHAR = ' ';

	public function update(): void {
		$this->refreshCrontab($this->crontabContent());
	}

	private function crontabContent(): string {
		$s = '';
		foreach (Model::where('enabled', true)->get() as $cron) {
			$s .= Helpers\TimeFormat::formatTime($cron->time) . self::TAB_CHAR;
			//$s .= 'dev' . self::TAB_CHAR;
			$s .= $cron->toConsoleJob() . "\n";
		}

		return $s;
	}

	private function refreshCrontab($content): void {
		$temp = tmpfile();
		$filename = stream_get_meta_data($temp)['uri'];
		fwrite($temp, $content);

		shell_exec('crontab ' . $filename);

		fclose($temp);
	}
}