<?php
return array(
	'_root_' => 'account/index',
	'login' => 'account/login',
	'register' => 'account/register',
	'settings' => 'account/settings',
	'logout' => 'account/logout',
	'account/delete' => 'account/delete_account',
	'credits' => 'account/credits',
	'api/engines' => 'engines/api_engines',
	'api/engines/save' => 'engines/api_engine_save',
	'api/engines/delete/(:num)' => 'engines/api_engine_delete/$1',
);
