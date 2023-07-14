<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('', 'API\V1\UsersController@index');
        $router->post('', 'API\V1\UsersController@store');
        $router->put('', 'API\V1\UsersController@updateInfo');
        $router->put('change-password', 'API\V1\UsersController@updatePassword');
        $router->delete('', 'API\V1\UsersController@delete');
    });

    $router->group(['prefix' => 'categories'], function () use ($router) {
        $router->get('', 'API\V1\CategoriesController@index');
        $router->post('', 'API\V1\CategoriesController@store');
        $router->delete('', 'API\V1\CategoriesController@delete');
        $router->put('', 'API\V1\CategoriesController@update');
    });

    $router->group(['prefix' => 'quizzes'], function () use ($router) {
        $router->get('', 'API\V1\QuizzesController@index');
        $router->post('', 'API\V1\QuizzesController@store');
        $router->delete('', 'API\V1\QuizzesController@delete');
        $router->put('', 'API\V1\QuizzesController@update');
    });

    $router->group(['prefix' => 'questions'], function () use ($router) {
        $router->post('', 'API\V1\QuestionsController@store');
        $router->delete('', 'API\V1\QuestionsController@delete');
    });
});