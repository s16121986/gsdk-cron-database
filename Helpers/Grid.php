<?php

namespace Gsdk\CronDatabase\Helpers;

class Grid {

	private $commands = [];

	public function setCommands($commands): static {
		$this->commands = $commands;
		return $this;
	}

	public function addDefaultColumns(): static {
		$commands = $this->commands;

		return $this
			->addColumn('enabled', 'boolean', ['text' => 'Включена'])
			//->addColumn('command', 'text', ['text' => 'Комманда'])
			->addColumn('command', 'text', [
				'text' => 'Комманда',
				'renderer' => fn($row) => $commands[$row->command] ?? ''
			])
			->addColumn('time', 'text', [
				'text' => 'Время',
				'renderer' => [TimeFormat::class, 'renderTime']])
			//->addColumn('user', 'text', ['text' => 'Пользователь'])
			->addColumn('log', 'text', [
				'text' => 'Последнее выполнение',
				'renderer' => [StatusRenderer::class, 'render']])
			->addColumn('run', 'text', [
				'text' => '',
				'renderer' => function ($row) {
					return '<a href="#" class="" data-status="' . $row->last_status . '" data-id="' . $row->id . '">Запустить</a>';
				}]);
	}
}