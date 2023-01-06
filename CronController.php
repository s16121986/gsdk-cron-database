<?php

namespace Gsdk\CronDatabase;

use Gsdk\CronDatabase\Eloquent\Model;
use Gsdk\CronDatabase\Support\CommandsReader;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class CronController extends Controller {

	protected CommandsReader $commandReader;

	public function __construct() {
		$this->commandReader = new CommandsReader();
	}

	/*public function index(Request $request) {
		$grid = $this->gridFactory();

		return view('', [
			'grid' => $grid
		]);
	}

	public function edit(Request $request, $id) {

	}

	public function create(Request $request) {

	}*/

	public function data(Request $request, $id) {
		$cron = Model::find($id);
		if (!$cron)
			return abort(404);

		$data = $cron->toArray();
		if ($cron->last_status != 0)
			$data['status_html'] = Helpers\StatusRenderer::render($cron);

		return $data;
	}

	public function run(Request $request, $id) {
		$cron = Model::find($id);
		if (!$cron)
			return abort(404);

		(new Support\CronConsole())->runBackground($cron);

		return ['status' => 'ok'];
	}

	public function delete(Request $request, $id) {
		$cron = Model::find($id);
		if (!$cron)
			return abort(404);

		$cron->delete();

		$this->updateCrontab();

		return $this->redirectToIndex();
	}

	protected function bootCommands() {
		$this->commandReader->loadFromArtisan();
	}

	protected function gridFactory() {
		$grid = new Helpers\Grid();

		$this->bootCommands();

		$grid->setCommands($this->commandReader->all());

		$this->bootGrid($grid);

		return $grid;
	}

	protected function bootGrid($grid) {
		$grid->addDefaultColumns();
	}

	protected function formFactory() {
		$form = new Helpers\Form();

		$this->bootCommands();

		$form->setCommands($this->commandReader->all());

		$this->bootForm($form);

		return $form;
	}

	protected function bootForm($form) {
		$form->addDefaultElements();
	}

	protected function redirectToIndex() {
		return redirect('home');
	}

	protected function updateCrontab() {
		(new Support\CrontabUpdater())->update();
	}

}
