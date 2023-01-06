<?php

namespace Gsdk\CronDatabase\Helpers;

class StatusRenderer {

	public static function render($job): string {
		if (!$job->last_executed)
			return '';

		return match ($job->last_status) {
			1 => '<span class="valid">' . $job->last_executed->format('datetime') . '</span>',
			2 => '<span class="invalid" title="' . htmlspecialchars($job->last_log) . '">' . $job->last_executed->format('datetime') . '</span>',
			3 => '<span class="">PROCESSING</span>',
			default => '',
		};
	}

}