<?php

namespace Gsdk\CronDatabase\Helpers;

class Form {

	private $commands = [];

	public function setCommands($commands): static {
		$this->commands = $commands;
		return $this;
	}

	public function addDefault() {
		return $this
			->addElement('command', 'select', [
				'label' => 'Комманда',
				'required' => true,
				'emptyItem' => '',
				'items' => $this->commands
			])
			->addElement('arguments', 'text', ['label' => 'Параметры'])
			->addElement('time', 'text', ['label' => 'Время'])
			->addElement('description', 'textarea', ['label' => 'Описание'])
			//->addElement('user', 'text', ['label' => 'Пользователь'])
			->addElement('enabled', 'checkbox', ['label' => 'Включена']);
	}
}
