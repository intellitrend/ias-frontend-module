<?php declare(strict_types = 1);

namespace Modules\IAS\Actions;

use CControllerResponseData;
use CControllerResponseFatal;
use CController as CAction;

require_once "IAS.php";

/**
 * Ias module action.
 */
class Service extends CAction {

	/**
	 * Initialize action. Method called by Zabbix core.
	 *
	 * @return void
	 */
	public function init(): void {
		/**
		 * Disable SID (Sessoin ID) validation. Session ID validation should only be used for actions which involde data
		 * modification, such as update or delete actions. In such case Session ID must be presented in the URL, so that
		 * the URL would expire as soon as the session expired.
		 */
		if (method_exists($this, 'disableSIDvalidation')) {
			$this->disableSIDvalidation();
		} else {
			$this->disableCsrfValidation();
		}
	}

	/**
	 * Check and sanitize user input parameters. Method called by Zabbix core. Execution stops if false is returned.
	 *
	 * @return bool true on success, false on error.
	 */
	protected function checkInput(): bool {
		$fields = [
			'serviceid' => 'uint64|required',
			'refresh' => 'required|in 0,1',
		];

		$ret = $this->validateInput($fields);

		if (!$ret) {
			$this->setResponse(new CControllerResponseFatal());
		}

		return $ret;
	}

	/**
	 * Check if the user has permission to execute this action. Method called by Zabbix core.
	 * Execution stops if false is returned.
	 *
	 * @return bool
	 */
	protected function checkPermissions(): bool {
		$permit_user_types = [USER_TYPE_ZABBIX_USER, USER_TYPE_ZABBIX_ADMIN, USER_TYPE_SUPER_ADMIN];
		return in_array($this->getUserType(), $permit_user_types);
	}

	/**
	 * Prepare the response object for the view. Method called by Zabbix core.
	 *
	 * @return void
	 */
	protected function doAction() {
		$data = IAS::service($this->getInput('serviceid'), $this->getInput('refresh'));
		$this->setResponse(new CControllerResponseData($data));
	}
}