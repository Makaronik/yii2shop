<?php


namespace app\assets;


use yii\web\AssetBundle;

class AuthAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css',
        'adminlte/bower_components/font-awesome/css/font-awesome.min.css',
        'adminlte/bower_components/Ionicons/css/ionicons.min.css',
        'adminlte/dist/css/AdminLTE.min.css',
        'adminlte/plugins/iCheck/square/blue.css',

    ];
    public $js = [
        'js/admin.js',
        'adminlte/plugins/iCheck/icheck.min.js',
        'adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}