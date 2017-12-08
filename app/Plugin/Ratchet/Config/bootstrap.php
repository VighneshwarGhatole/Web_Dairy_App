<?php

/**
 * This file is part of Ratchet for CakePHP.
 *
 ** (c) 2012 - 2013 Cees-Jan Kiewiet
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


/**
 * Configuration
 */
Configure::write('Ratchet', [
	'Client' => [
		'retryDelay' => 5000, // Not the best option but it speeds up development
		'maxRetries' => 25, // Keep on trying! (Also not the best option)
	],
	'Connection' => [
		'websocket' => [
			'address' => '0.0.0.0',
			'port' => WEB_SOCKET_PORT,
		],
		'external' => [
			'hostname' => $_SERVER['HTTP_HOST'],
			'port' => isset($_SERVER["HTTPS"]) ? $_SERVER['SERVER_PORT']: WEB_SOCKET_PORT,
			'path' => WEB_SOCKET_PATH,
			'secure' => isset($_SERVER["HTTPS"] ) ? true : false,
		],
		'keepaliveInterval' => 23, // Why 23? Because NGINX kills after 30 seconds, set to 0 to disable
	],
]);

App::uses('CakeEventManager', 'Event');

/**
 * Client services listener
 */

App::uses('RatchetKeepAliveListener', 'Ratchet.Event');
CakeEventManager::instance()->attach(new RatchetKeepAliveListener());

/**
 * PCNTL listener
 */
// App::uses('PcntlListener', 'Ratchet.Event');
// CakeEventManager::instance()->attach(new PcntlListener());
