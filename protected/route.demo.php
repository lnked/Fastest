<?php

require_once('route.class.php');

// Define our routes
Route::$debug = false;
Route::init();

// Our login page
Route::add(APP_URL_LOGIN,  'login.php');
Route::add(APP_URL_LOGOUT, 'logout.php');

Route::add('/signup',      'signup.php');

//Route::add('/accounts/profile',   'accounts/profile.php');
//Route::add('/accounts/settings',  'accounts/settings.php');

Route::add( '/organization/new', 'organization/edit.php');
Route::add('^/organization/([0-9a-f]{8}-[^/]+)/edit$',    array('organization_guid'), 'organization/edit.php');
//Route::add('^/organization/([0-9a-f]{8}-[^/]+)/account$', array('organization_guid'), 'organization/account.php');
//Route::add('^/organization/([0-9a-f]{8}-[^/]+)/delete$',  array('organization_guid'), 'organization/delete.php');

Route::add('^/([^/]+)/_tag$',                               array('organization_slug'),                'tag/list.php');
Route::add('^/([^/]+)/_tag/new$',                           array('organization_slug'),                'tag/edit.php');
Route::add('^/([^/]+)/_tag/([0-9a-f]{8}-[^/]+)$',           array('organization_slug', 'tag_guid'),    'tag/detail.php');
Route::add('^/([^/]+)/_tag/([0-9a-f]{8}-[^/]+)/edit$',      array('organization_slug', 'tag_guid'),    'tag/edit.php');

Route::add('^/([^/]+)/_team$',                              array('organization_slug'),               'team/list.php');
Route::add('^/([^/]+)/_team/new$',                          array('organization_slug'),               'team/edit.php');
Route::add('^/([^/]+)/_team/([0-9a-f]{8}-[^/]+)$',          array('organization_slug', 'team_guid'),  'team/detail.php');
Route::add('^/([^/]+)/_team/([0-9a-f]{8}-[^/]+)/edit$',     array('organization_slug', 'team_guid'),  'team/edit.php');

Route::add('^/([^/]+)/_topic$',                             array('organization_slug'),               'topic/list.php');
Route::add('^/([^/]+)/_topic/new$',                         array('organization_slug'),               'topic/edit.php');
Route::add('^/([^/]+)/_topic/([0-9a-f]{8}-[^/]+)/edit$',    array('organization_slug', 'topic_guid'), 'topic/edit.php');

Route::add('^/([^/]+)/_settings$',                          array('organization_slug'),               'settings/home.php');

Route::add('^/([^/]+)/_action$',                            array('organization_slug'),                  'action/list.php');
Route::add('^/([^/]+)/_action/new$',                        array('organization_slug'),                  'action/edit.php');
Route::add('^/([^/]+)/_action/([0-9a-f]{8}-[^/]+)$',        array('organization_slug', 'action_guid'),   'action/detail.php');
Route::add('^/([^/]+)/_action/([0-9a-f]{8}-[^/]+)/edit$',   array('organization_slug', 'action_guid'),   'action/edit.php');

Route::add('^/([^/]+)/([^/]+)/_item/([0-9a-f]{8}-[^/]+)$', array('organization_slug', 'topic_key', 'item_guid'), 'item/detail.php');

Route::add('/privacy', 'page-privacy.php');
Route::add('/terms',   'page-terms.php');
Route::add('/contact', 'page-contact.php');
Route::add('/pricing', 'page-pricing.php');

// Default Route
if ( is_authenticated() ) {
    Route::add('/', 'welcome.php');
} else {
    Route::add('/', 'home.php');
}

// Check dynamic slug routes ( This is the most expensive route )
Route::add('^/([a-z0-9-]+)(?:/([^/]+)(?:/([^/]+))?)?', array('primary', 'secondary', 'tertiary'), array('dynamic' => 'check_org_slug'));

// Default 404 route
Route::add('^.*$', '404.php');

Route::run();