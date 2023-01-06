<?php

namespace Gsdk\CronDatabase\Support;

use Illuminate\Support\Facades\Artisan;

class CommandsReader {

	private array $commands = [];

	public function loadFromArtisan(): static {
		foreach (Artisan::all() as $key => $command) {
			$d = $command->getDescription();
			$this->commands[$key] = $key . ($d ? ' (' . $d . ')' : '');
		}

		asort($this->commands);

		return $this;
	}

	public function filter(callable $fn): static {
		foreach ($this->commands as $key => $command) {
			if (!$fn($command))
				unset($this->commands[$key]);
		}

		return $this;
	}

	public function filterCronable(): static {
		return $this->filter(fn($command) => isset($command->cronable));
	}

	public function filterNamespace(string $namespace): static {
		return $this->filter(fn($command) => str_starts_with($command::class, $namespace));
	}

	public function all(): array {
		return $this->commands;
	}
}