<!doctype html>
<html lang="en" ng-app="UPA_Pagos">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>Universidad Politecnica de Aguascalientes</title>
        <link href="css/kendo.common.min.css" rel="stylesheet" type="text/css"/>
        <link href="css/kendo.default.min.css" rel="stylesheet" type="text/css"/>
        <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.css" />
        <link href="bower_components/handsontable-0.18.0/dist/handsontable.full.min.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="css/app.css"/>
        <script src="bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
        <script src="bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/external_libs/jszip.min.js" type="text/javascript"></script>
       
    </head>
    <body>
        <div ng-view></div>

        <!--        <div>Angular seed app: v<span app-version></span></div>-->

        <!-- In production use:
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
        -->

        <!-- Libs -->

        <script src="bower_components/handsontable-0.18.0/dist/handsontable.full.js" type="text/javascript"></script>
        <script src="bower_components/angular/angular.min.js" type="text/javascript"></script>
        <script src="bower_components/angular/language/angular-locale_es-mx.js" type="text/javascript"></script>
        <script src="bower_components/angular-cache/dist/angular-cache.js" type="text/javascript"></script>
        <script src="bower_components/angular-route/angular-route.min.js" type="text/javascript"></script>
        <script src="bower_components/angular-multi-select-3.0.0/isteven-multi-select.js" type="text/javascript"></script>
         <script src="js/external_libs/kendo.all.min.js" type="text/javascript"></script>
        <script src="js/app.js"></script>


        <!-- Controllers -->
        <script src="js/controllers/MainCtrl.js" type="text/javascript"></script>
        <script src="js/controllers/controllers.js" type="text/javascript"></script>
        <script src="js/controllers/LoginCtrl.js" type="text/javascript"></script>
        <script src="js/controllers/AdminCtrl.js" type="text/javascript"></script>
        <script src="js/controllers/AlumnosCtrl.js" type="text/javascript"></script>
        <script src="js/controllers/AdministracionCtrl.js" type="text/javascript"></script>
        <script src="js/controllers/GeneralesCntrl.js" type="text/javascript"></script>
        <script src="js/controllers/UsefulFunctionsCntrl.js" type="text/javascript"></script>
        <script src="js/controllers/ModalsCtrl/GeneralesModalCtrl.js" type="text/javascript"></script>
        <!-- Services -->
        <script src="js/services/AdminServices.js" type="text/javascript"></script>
        <script src="js/services/AuthInterceptor.js" type="text/javascript"></script>
        <script src="js/services/authService.js" type="text/javascript"></script>
        <script src="js/services/services.js" type="text/javascript"></script>
        <script src="js/services/studentServices.js" type="text/javascript"></script>
        <script src="js/services/cacheService.js" type="text/javascript"></script>
        <!-- directives -->
        <script src="js/directives/directives.js" type="text/javascript"></script>
        <script src="js/filters/filters.js" type="text/javascript"></script>
        <!-- Modules-->
        <script src="js/modules/checkmodule.js" type="text/javascript"></script>

        <!-- Other Scripts -->
        <script src="bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js" type="text/javascript"></script>
    </body>
</html>
