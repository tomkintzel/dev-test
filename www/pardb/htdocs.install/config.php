<?php

use ParDb\Entities\Email;
use ParDb\Entities\Form;
use ParDb\Entities\Opportunity;
use ParDb\Entities\Prospect;

$db['driver'] = 'pdo_mysql';
$db['host'] = 'db';
$db['port'] = '';
$db['name'] = 'pardb';
$db['user'] = 'root';
$db['password'] = '';
$db['table_prefix'] = '';
$db['charset'] = 'UTF8';

$config['db'] = $db;

/*
 * Die Daten, welche für die Anmeldung bei der Pardot-API benutzt werden
 */
$pardot['email'] = 'marketingusersalesforce@mindsquare.de';
$pardot['password'] = 'c#WE$L%j3RsHx2g';
$pardot['user-key'] = '5f74cb59e64bd239a6dd237b6100a0f3';

$config['pardot'] = $pardot;

/*
 * Perioden sollten wie folgt definiert werden:
 * http://php.net/manual/de/dateinterval.construct.php (interval_spec Perioden Bezeichner)
 * Datums- und Zeitangaben optimalerweise nach ISO 8601 (https://de.wikipedia.org/wiki/ISO_8601, https://xkcd.com/1179/)
 * 	   z.B. 1970-01-01,
 * 			1970-01-01T00:00+01:00
 */
$update['range'] = new DateInterval('P4W');
$update['refresh-range'] = new DateInterval('P1M');
$update['earliest-date'] = new DateTimeImmutable('2015-01-01');
$update['do-refresh'] = false;

$form['range'] = new DateInterval('P4Y');

$email['do-refresh'] = true;

$update[Form::class] = $form;
$update[Email::class] = $email;
$update[Prospect::class]['earliest-date'] = new DateTimeImmutable('2015-04-20');

$config['update'] = $update;

/*
 * Jegliche URL- und Datei- / Ordner-Angaben ohne Trailing-Slash
 * url = Welche URL ab Root die Anwendung hat
 */
$config['pardb']['url'] = '';
$config['pardb']['sql'] = '/sql';

return $config;
