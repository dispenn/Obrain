<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "site_public/index";
$route['404_override']  = '';
$route['contacts']      = 'site_public/contacts';
$route['department']      = 'site_public/department';
$route['department/(:any)'] = 'site_public/department/$1';
$route['news']      = 'site_public/news';
$route['news/(:any)'] = 'site_public/news/$1';
$route['articles']      = 'site_public/articles';
$route['articles/(:any)'] = 'site_public/articles/$1';
$route['anketa']      = 'site_public/anketa';
$route['promo']      = 'site_public/promo';
$route['promo/(:any)'] = 'site_public/promo/$1';
$route['gallery']      = 'site_public/gallery';
$route['gallery/(:any)'] = 'site_public/gallery/$1';
$route['catalog']      = 'site_public/catalog';
$route['catalog/(:any)'] = 'site_public/catalog/$1';
$route['about']      = 'site_public/about';
$route['vacancy']      = 'site_public/vacancy';
$route['autorization']  = 'autorization';
$route['administrator'] = 'administrator/index';

$route['logout']        = 'autorization/logout';
$route['login']         = 'autorization/login';

$route['forgot_password']        = 'site_public/forgot_password';
$route['reset_password'] = 'site_public/reset_password';

$route['index2']      = 'site_public/index2';
$route['success']      = 'site_public/success';
$route['news/(:any)'] = 'site_public/news/$1';
$route['news']      = 'site_public/news';
$route['news_single/(:any)']      = 'site_public/news_single/$1';
$route['news_single']      = 'site_public/news_single';
