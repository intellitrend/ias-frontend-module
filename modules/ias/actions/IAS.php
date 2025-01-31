<?php declare(strict_types = 1);

namespace Modules\IAS\Actions;

use API;
use RuntimeException;

class IAS {

	public static function getBackendUrl() {
        static $backendUrl = null;

        if ($backendUrl === null) {
            // try to use global macro first
            $db_macros = API::UserMacro()->get([
                'output' => ['value'],
                'globalmacro' => true,
                'filter' => [
                    'macro' => '{$IAS_URL}'
                ]
            ]);

            if (count($db_macros) > 0) {
                $backendUrl = $db_macros[0]["value"];
            } else {
                // try environment variable as fallback
                $backend_url = getenv('IAS_URL');
                if ($backend_url !== false && !empty($backend_url)) {
                    $backendUrl = $backend_url;
                } else {
                    $backendUrl = '';
                }
            }
        }

		if (empty($backendUrl)) {
			throw new RuntimeException('IAS is unconfigured. Please either set the {$IAS_URL} global macro or the environment variable IAS_URL to contain the URL to your IAS instance.');
		}

        return $backendUrl;
	}

    public static function service($serviceid, $refresh) {
        $filePath = '/api/';
        if ($serviceid === null) {
            $filePath .= 'services?';
        } else {
            $filePath .= 'service/' . $serviceid . '?';
        }

        $token = \CWebUser::$data['sessionid'];
        $filePath .= "embed=1&token=$token";

        if ($refresh) {
            $filePath .= '&refresh=1';
        }

        $mime = 'application/json';

        $fileURL = self::getBackendUrl() . $filePath;
        $data = @file_get_contents($fileURL);
        if ($data === false) {
            throw new RuntimeException('Cannot communicate with IAS backend server');
        }

        return [
			'main_block' => $data,
			'mime_type' => $mime,
		];
    }

    public static function file($fileName) {
        $filePath = '';
        switch ($fileName) {
            case 'embed':
                $mime = 'text/html';
                $filePath = '/?embed=1';
                break;
            case 'ias.js':
                $mime = 'application/javascript';
                $filePath = '/assets/ias.js?embed=1';
                break;
            case 'ias.css':
                $theme = getUserTheme(\CWebUser::$data);
                $mime = 'text/css';
                $filePath = "/assets/ias.css?embed=1&theme=$theme";
                break;
            default:
                throw new RuntimeException('Invalid or missing file');
        }

        $fileURL = self::getBackendUrl() . $filePath;
        $data = @file_get_contents($fileURL);
        if ($data === false) {
            throw new RuntimeException('Cannot communicate with IAS backend server');
        }

        return [
			'main_block' => $data,
			'mime_type' => $mime,
		];
    }
}