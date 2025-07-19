<?php

use Core\Router; 


// student routes
$router->post('/createStudent', 'StudentController@create');
$router->get('/showStudent', 'StudentController@getAll');
$router->get('/showStudent/{id}', 'StudentController@getById');
$router->post('/updateStudent', 'StudentController@update');
$router->delete('/deleteStudent/{id}', 'StudentController@delete');
// now to test this we will use postman 