<?php declare(strict_types = 1);

namespace Modules\Ias;

use APP;
use API;
use CController as CAction;

/**
 * Please see Core\CModule class for additional reference.
 */
class Module extends \Core\CModule {

	private static $_instance;

	public static function getBackendUrl() {
		// try to use global macro first
		$db_macros = API::UserMacro()->get([
			'output' => ['value'],
			'globalmacro' => true,
			'filter' => [
				'macro' => '{$IAS_URL}'
			]
		]);

		if (count($db_macros) > 0) {
			return $db_macros[0]["value"];
		}

		// try environment variable as fallback
		$backend_url = getenv('IAS_BACKEND_URL');
		if ($backend_url !== false && !empty($backend_url)) {
			return $backend_url;
		}

		return '';
	}

	/**
	 * Initialize module.
	 */
	public function init(): void {
		self::$_instance = $this;

		// Initialize main menu (CMenu class instance).
		APP::Component()->get('menu.main')
			->findOrAdd(_('Services'))
				->getSubmenu()
					->add((new \CMenuItem(_('Advanced Services')))
						->setAction('ias.view')
		);
	}

	/**
	 * Event handler, triggered before executing the action.
	 *
	 * @param CAction $action  Action instance responsible for current request.
	 */
	public function onBeforeAction(CAction $action): void {
	}

	/**
	 * Event handler, triggered on application exit.
	 *
	 * @param CAction $action  Action instance responsible for current request.
	 */
	public function onTerminate(CAction $action): void {
	}
}