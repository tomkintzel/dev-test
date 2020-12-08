<?php

use Cascade\Cascade;

require_once 'bootstrap.php';

$request = getRequest();

function update() {
	global $parDb;
	ob_start();

	if (isset($_GET['entities'])) {
		$parDb->update($_GET['entities']);
	} else {
		$parDb->update();
	}

	return ob_get_clean();
}

switch ($request[0]):
	case 'update':
		$doLog = isset($_GET['log-output']) && $_GET['log-output'] === 'true';

		if ($doLog) {
			file_put_contents(TEMP_LOG_LOCATION, '');
		}

		$output = update();
		$response = [
			'output' => $output
		];

		if ($doLog) {
			$logOutput = [];
			$lines = file(TEMP_LOG_LOCATION);

			foreach ($lines as $line) {
				$logOutput[] = json_decode($line);
			}

			$response['logOutput'] = $logOutput;
		}

		echo json_encode($response);
		break;
	case 'request':
		$connector = $parDb->getConnector();

		$endpoint = $_GET['endpoint'];
		$operation = $_GET['operation'];

		foreach ($_GET['parameters'] as $parameter) {
			$parameters[$parameter['name']] = json_decode($parameter['value']);
		}

		$connector->authenticate();

		Cascade::getLogger('ParDb')->info('API-Test-Abfrage, Key: ' . $connector->getApiKey(), $parameters);

		$apiResponse = $connector->post($endpoint, $operation, $parameters);

		$response = $connector->getResultCount();
		$response .= '<br>';
		$response .= htmlspecialchars(print_r($apiResponse, true));
		echo $response;

		break;
	case 'init':
		$parDb->init();
		break;
	case 'update-schema':
		$parDb->updateSchema();
		break;
	case 'read-sql':
		$parDb->fachbereicheEinlesen();
		$parDb->readRawSql();
		$parDb->createViews();
		break;
	default:
		require_once('ui.php');
		break;
endswitch;


