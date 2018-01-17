<?php

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    $routes->connect('/', ['controller' => 'Home', 'action' => 'index']);

    $routes->connect('/auth', ['controller' => 'OAuth', 'action' => 'authen']);

    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    $routes->connect('/lam-them-gio', ['controller' => 'Overtimes', 'action' => 'index']);

    $routes->connect('/dang-nhap', ['controller' => 'Users', 'action' => 'login']);

    $routes->connect('/dang-xuat', ['controller' => 'Users', 'action' => 'logout']);

    $routes->connect('/danh-sach-du-an', ['controller' => 'Projects', 'action' => 'listProject']);

    $routes->connect('/chi-tiet-du-an/*', ['controller' => 'Projects', 'action' => 'viewProject']);

    $routes->connect('/tao-du-an', ['controller' => 'Projects', 'action' => 'add']);

    $routes->connect('/sua-du-an/*', ['controller' => 'Projects', 'action' => 'edit']);


    $routes->connect('/du-an-da-va-dang-tham-gia/*', ['controller' => 'Projects', 'action' => 'viewByUserId']);

    $routes->connect('/du-an-tham-gia', ['controller' => 'Projects', 'action' => 'index']);

    $routes->connect('/lich-lam-them', ['controller' => 'ManageOvertimes', 'action' => 'calendar']);

    $routes->connect('/thong-tin-du-an/*', ['controller' => 'Projects', 'action' => 'view']);

    $routes->connect('/tao-yeu-cau-lam-them', ['controller' => 'ManageOvertimes', 'action' => 'addrequest']);

    $routes->connect('/nhom-truong-yeu-cau-lam-them', ['controller' => 'ManageOvertimes', 'action' => 'addrequestbyleader']);

    $routes->connect('/lam-them-gio-cho-phe-duyet', ['controller' => 'Overtimes', 'action' => 'index1']);

    $routes->connect('/lam-them-gio-da-phe-duyet', ['controller' => 'Overtimes', 'action' => 'index2']);

    $routes->connect('/lam-them-gio-da-tu-choi', ['controller' => 'Overtimes', 'action' => 'listDenyOvertime']);

    $routes->connect('/danh-sach-lam-them-cua-ban-than', ['controller' => 'Overtimes', 'action' => 'listOvertime']);

    $routes->connect('/sua-yeu-cau-lam-them-gio/*', ['controller' => 'Overtimes', 'action' => 'editOvertime']);

    $routes->fallbacks(DashedRoute::class);
});

Plugin::routes();
