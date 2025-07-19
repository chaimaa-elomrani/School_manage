<?php

use Core\Router; 

// student routes
$router->post('/createStudent', 'StudentController@create');
$router->get('/showStudent', 'StudentController@getAll');
$router->get('/showStudent/{id}', 'StudentController@getById');
$router->post('/updateStudent/{id}', 'StudentController@update');
$router->delete('/deleteStudent/{id}', 'StudentController@delete');

// teacher routes
$router->post('/createTeacher', 'TeacherController@create');
$router->get('/showTeacher', 'TeacherController@getAll');
$router->get('/showTeacher/{id}', 'TeacherController@getById');
$router->post('/updateTeacher', 'TeacherController@update');
$router->delete('/deleteTeacher/{id}', 'TeacherController@delete');
// now to test this we will use postman 
