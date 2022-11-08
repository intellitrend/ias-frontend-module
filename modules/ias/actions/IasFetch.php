<?php declare(strict_types = 1);

namespace Modules\Ias\Actions;

use CControllerResponseData;
use CControllerResponseFatal;
use CController as CAction;

/**
 * Ias module action.
 */
class IasFetch extends CAction {

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
		$mime = 'text/plain';
		$backend_url = \Modules\Ias\Module::getBackendUrl();

		if (empty($backend_url)) {
			$error = 'IAS is unconfigured. Please edit the manifest.json of your IAS module and fill out "backend_url" or set the environment variable IAS_BACKEND_URL.';
		} else {
			$file = '';
			$file_choice = isset($_GET['file']) ? $_GET['file'] : '';
			switch ($file_choice) {
				case 'ias.js':
					$mime = 'application/javascript';
					$file = '/assets/ias.js?embed=1';
					break;
				case 'ias.css':
					$theme = getUserTheme(\CWebUser::$data);
					$mime = 'text/css';
					$file = "/assets/ias.css?embed=1&theme=$theme";
					break;
				case 'ias.services':
					$token = \CWebUser::$data['sessionid'];
					$mime = 'application/json';
					$file = "/api/services?embed=1&token=$token";
					break;
				case 'ias.services-refresh':
					$token = \CWebUser::$data['sessionid'];
					$mime = 'application/json';
					$file = "/api/services-refresh?embed=1&token=$token";
					break;
			}

			if ($file) {
				$ias_file_url = $backend_url . $file;
				$data = @file_get_contents($ias_file_url);
				if ($data === false) {
					$data = 'Cannot communicate with IAS backend server.';
				}
			} else {
				$data = 'Invalid or missing file';
			}
		}
		
		$response = new CControllerResponseData([
			'main_block' => $data,
			'mime_type' => $mime
		]);
		$this->setResponse($response);
	}
}