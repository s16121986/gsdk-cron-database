<?php

namespace Gsdk\CronDatabase\Eloquent;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel {

	protected $artisanPath = 'artisan';

	public $timestamps = false;

	protected $table = 's_crontab';

	protected $fillable = [
		'enabled',
		'time',
		'user',
		'command',
		'arguments',
		'description',
		//'pid',
		'last_executed',
		'last_status',
		'last_log'
	];

	protected $casts = [
		'last_executed' => 'datetime:Y-m-d H:i:s'
	];

	public function __toString() {
		return (string)$this->command;
	}

	public function toConsoleJob(): string {
		return 'php -q '
			. base_path($this->artisanPath)
			. ' cron:job ' . $this->id;
	}

	public function toConsoleCommand(): string {
		return 'php '
			. base_path($this->artisanPath) . ' '
			. $this->command
			. ($this->arguments ? ' ' . $this->arguments : '');
	}

}
