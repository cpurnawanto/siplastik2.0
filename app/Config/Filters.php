<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
	// Makes reading things below nicer,
	// and simpler to change out script that's used.
	public $aliases = [
		'csrf'     => \CodeIgniter\Filters\CSRF::class,
		'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
		'honeypot' => \CodeIgniter\Filters\Honeypot::class,
		'checkPegawaiLogin' => \App\Filters\PegawaiLoggedInFilter::class,
		'checkStrukturalLogin' => [
			\App\Filters\PegawaiLoggedInFilter::class,
			\App\Filters\StrukturalFilter::class
		],
		'checkAdminLogin' => [
			\App\Filters\PegawaiLoggedInFilter::class,
			\App\Filters\AdminFilter::class
		]
	];

	// Always applied before every request
	public $globals = [
		'before' => [
			'csrf' => ['except' => ['api/*']],
			'checkPegawaiLogin' => ['except' => ['/', 'user/*', 'admin/*', 'admin', 'struktural', 'struktural/*', 'api/struktural/*']]
		],
		'after'  => [
			'toolbar',
			//'honeypot'
		],
	];

	// Works on all of a particular HTTP method
	// (GET, POST, etc) as BEFORE filters only
	//     like: 'post' => ['CSRF', 'throttle'],
	public $methods = [];

	// List filter aliases and any before/after uri patterns
	// that they should run on, like:
	//    'isLoggedIn' => ['before' => ['account/*', 'profiles/*']],
	public $filters = [
		'checkAdminLogin' => ['before' => ['admin/*', 'admin', 'berkas/create']],
		'checkStrukturalLogin' => ['before' => ['struktural/*', 'struktural', 'api/struktural/*']],
	];
}
