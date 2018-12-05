<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->resource('comments', 'CommentController');
    $router->get('/', 'HomeController@index');
    Route::prefix('hall')->group(function (Router $router) {
      $router->resource('alltasks', 'TaskController');
      $router->post('alltasks/tasksget', 'TaskController@tasksget');
      $router->post('alltasks/taskget', 'TaskController@taskget');
      //我的任务
      $router->resource('mytasks', 'MyTaskController');
      //已超时任务
      $router->resource('losttasks', 'LostTaskController');
      //已完结任务
      $router->resource('donetasks', 'DoneTaskController');
    });
    Route::prefix('users')->group(function (Router $router) {
      $router->resource('infos', 'UinfosController');
      $router->resource('qtype', 'QuestionController');
    });
    //$router->resource('hall', 'TaskController');
});
