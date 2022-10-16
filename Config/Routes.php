<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
 $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');
$routes->post('/authenticate', 'Auth::ldap_auth');

$routes->get('/department', 'Department::index');
$routes->get('/skeleton', 'Skeleton::index');

//restfully
$routes->get('/api/department', 'APIDepartment::listing');
$routes->post('/api/employee', 'APIEmployee::listing');
$routes->post('/api/employee/insert', 'APIEmployee::insert_data');
$routes->post('/api/employee/update/(:num)', 'APIEmployee::update_data/$1');
$routes->post('/api/employee/delete/(:num)', 'APIEmployee::delete_data/$1');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Include Modules Routing Files
 * --------------------------------------------------------------------
 *
 */
$uri = service('uri');
$module = ucfirst($uri->getSegment(1));

if(file_exists(ROOTPATH.'/app/Modules/' . $module . '/Routes.php')){
    $file =  scandir(ROOTPATH.'/app/Modules/'.$module); 
    if (in_array('Routes.php', $file)) {
        include ROOTPATH.'/app/Modules/' . $module . '/Routes.php';
    }
}
