<!doctype html>
<html lang="en" ng-app="UPA_Pagos">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>Universidad Politecnica de Aguascalientes</title>
        <link href="css/kendo.common.min.css?ver=<?=$app_version?>" rel="stylesheet" type="text/css"/>
        <link href="css/kendo.default.min.css?ver=<?=$app_version?>" rel="stylesheet" type="text/css"/>
        <link href="bower_components/bootstrap/dist/css/bootstrap.min.css?ver=<?=$app_version?>" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.css?ver=<?=$app_version?>" />
        <link href="bower_components/handsontable-0.18.0/dist/handsontable.full.min.css?ver=<?=$app_version?>" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="css/app.css?ver=<?=$app_version?>"/>
        <script src="bower_components/jquery/dist/jquery.min.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="bower_components/bootstrap/dist/js/bootstrap.min.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/external_libs/jszip.min.js?ver=<?=$app_version?>" type="text/javascript"></script>
    </head>
    <body>
        <div ng-view></div>

        <!--        <div>Angular seed app: v<span app-version></span></div>-->

        <!-- In production use:
        <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.0.7/angular.min.js"></script>
        -->

        <!-- Libs -->

        <script src="bower_components/handsontable-0.18.0/dist/handsontable.full.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="bower_components/angular/angular.min.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="bower_components/angular/language/angular-locale_es-mx.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="bower_components/angular-cache/dist/angular-cache.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="bower_components/angular-route/angular-route.min.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="bower_components/angular-multi-select-3.0.0/isteven-multi-select.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/external_libs/kendo.all.min.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/external_libs/lodash.min.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/external_libs/angularjs-dropdown-multiselect.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/app.js?ver=<?=$app_version?>"></script>


        <!-- Controllers -->
        <script src="js/controllers/MainCtrl.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/controllers/controllers.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/controllers/LoginCtrl.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/controllers/AdminCtrl.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/controllers/AlumnosCtrl.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/controllers/AdministracionCtrl.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/controllers/GeneralesCntrl.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/controllers/UsefulFunctionsCntrl.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/controllers/ModalsCtrl/GeneralesModalCtrl.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <!-- Services -->
        <script src="js/services/AdminServices.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/services/AuthInterceptor.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/services/authService.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/services/services.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/services/studentServices.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/services/cacheService.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <!-- directives -->
        <script src="js/directives/directives.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <script src="js/filters/filters.js?ver=<?=$app_version?>" type="text/javascript"></script>
        <!-- Modules-->
        <script src="js/modules/checkmodule.js?ver=<?=$app_version?>" type="text/javascript"></script>

        <!-- Other Scripts -->
        <script src="bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js?ver=<?=$app_version?>" type="text/javascript"></script>
    </body>
</html>
