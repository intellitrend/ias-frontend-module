<?php declare(strict_types = 1);

namespace Modules\Ias;

use APP;
use CController as CAction;
use CMenuItem;

// alias for Zabbix 6.0
if (!class_exists('Zabbix\Core\CModule') && class_exists('Core\CModule')) {
	class_alias('Core\CModule', 'Zabbix\Core\CModule');
}

use Zabbix\Core\CModule;

/**
 * Please see Core\CModule class for additional reference.
 */
class Module extends CModule {

	private static $_instance;

	/**
	 * Initialize module.
	 */
	public function init(): void {
		self::$_instance = $this;

		// Initialize main menu (CMenu class instance).
		APP::Component()->get('menu.main')
			->findOrAdd(_('Services'))
				->getSubmenu()
					->add((new CMenuItem(_('Advanced Services')))
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