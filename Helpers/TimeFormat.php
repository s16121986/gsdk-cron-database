<?php

namespace Gsdk\CronDatabase\Helpers;

class TimeFormat {

	private static array $timesAssoc = [
		'@weekly' => ['Еженедельно (02:00, Сб)', '0 2 * * 6'],
		'@daily' => ['Ежедневно (01:00)', '0 1 * * *'],
		'@hourly' => ['Ежедневно', '0 * * * *']
	];

	public static function formatTime($time) {
		foreach (static::$timesAssoc as $k => $v) {
			$time = str_replace($k, $v[1], $time);
		}

		return $time;
	}

	public static function renderTime($row) {
		if (isset(static::$timesAssoc[$row->time]))
			return static::$timesAssoc[$row->time][0];

		$s = $row->time;
		//$r = '(*|[0-9]+)';
		$n = '(\d+)';
		if (preg_match('/^' . $n . ' ' . $n . ' \* \* ' . $n . '$/', $s, $m))
			return 'Еженедельно (' . static::pad($m[2]) . ':' . static::pad($m[1]) . ', ' . $m[3] . ')';

		if (preg_match('/^' . $n . ' ' . $n . ' \* \* \*$/', $s, $m))
			return 'Ежедневно (' . static::pad($m[2]) . ':' . static::pad($m[1]) . ')';

		return $row->time;
	}

	private static function pad($s): string {
		return str_pad($s, 2, '0', STR_PAD_LEFT);
	}

}