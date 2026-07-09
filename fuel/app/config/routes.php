<?php
return array(
	'_root_' => 'account/index',
	'_404_' => 'quicksearch/not_found',
	'login' => 'account/login',
	'register' => 'account/register',
	'settings' => 'account/settings',
	'logout' => 'account/logout',
	'account/delete' => 'account/delete_account',
	'credits' => 'quicksearch/credits',
	'search/(:any)' => 'search/index/$1',
	'api/engines' => 'engines/api_engines',
	'api/engines/save' => 'engines/api_engine_save',
	'api/engines/delete/(:num)' => 'engines/api_engine_delete/$1',
);
