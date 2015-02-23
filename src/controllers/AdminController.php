<?php 
namespace Travel\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class AdminController {

    public function dashboard(Request $request, Application $app) {
        return $app['twig']->render('admin/dashboard.html', array());
    }

}
