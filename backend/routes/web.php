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

// room routes tested successfuly 
$router->post('/createRoom', 'RoomController@create');
$router->get('/showRooms', 'RoomController@getAll');
$router->get('/showRoom/{id}', 'RoomController@getById');
$router->post('/updateRoom/{id}', 'RoomController@update');
$router->delete('/deleteRoom/{id}', 'RoomController@delete');



$router->post('/createCourse', 'CourseController@create');
$router->get('/showCourses', 'CourseController@getAll');
$router->get('/showCourse/{id}', 'CourseController@getById');
$router->post('/updateCourse/{id}', 'CourseController@update');
$router->delete('/deleteCourse/{id}', 'CourseController@delete');


$router->post('/createSchedule', 'ScheduleController@create');
$router->get('/showSchedules', 'ScheduleController@getAll');
$router->get('/showSchedule/{id}', 'ScheduleController@getById');
$router->post('/updateSchedule/{id}', 'ScheduleController@update');
$router->delete('/deleteSchedule/{id}', 'ScheduleController@delete');
$router->post('/checkAvailability', 'ScheduleController@checkAvailability');


$router->post('/createPlanning', 'PlanningController@create');
$router->get('/showPlannings', 'PlanningController@getAll');
$router->get('/showPlanning/{id}', 'PlanningController@getById');
$router->post('/updatePlanning/{id}', 'PlanningController@update');
$router->delete('/deletePlanning/{id}', 'PlanningController@delete');


