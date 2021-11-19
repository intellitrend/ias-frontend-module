<?php declare(strict_types = 1);

namespace Modules\Ias\Actions;

use CControllerResponseData;
use CControllerResponseFatal;
use CController as CAction;

/**
 * Ias module action.
 */
class IasView extends CAction {

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
		$this->disableSIDvalidation();
	}

	/**
	 * Check and sanitize user input parameters. Method called by Zabbix core. Execution stops if false is returned.
	 *
	 * @return bool true on success, false on error.
	 */
	protected function checkInput(): bool {
		return true;
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
		$data = false;
		$error = '';

		global $IAS;
		if (!isset($IAS)) {
			$error = 'IAS is unconfigured. Please edit your zabbix.conf.php.';
		} else {
			$ias_url = $IAS['API_URL'];
			$ias_file_url = $ias_url . '/?embed=1';
			$data = @file_get_contents($ias_file_url);
			if ($data === false) {
				$error = 'Cannot communicate with IAS backend server.';
			}
		}

		$response = new CControllerResponseData([
			'main_block' => $data,
			'error' => $error,
			'theme' => getUserTheme(\CWebUser::$data),
		]);
		$response->setTitle(_('Advanced Services'));
		$this->setResponse($response);
	}
}