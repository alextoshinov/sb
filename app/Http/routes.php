<?php
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// OAuth, Login and Signup Routes.
Route::post('auth/twitter', 'AuthController@twitter');
Route::post('auth/facebook', 'AuthController@facebook');
Route::post('auth/foursquare', 'AuthController@foursquare');
Route::post('auth/instagram', 'AuthController@instagram');
Route::post('auth/github', 'AuthController@github');
Route::post('auth/google', 'AuthController@google');
Route::post('auth/linkedin', 'AuthController@linkedin');
Route::post('auth/login', 'AuthController@login');
Route::post('auth/signup', 'AuthController@signup');
Route::post('auth/unlink/{provider}', ['middleware' => 'auth', 'uses' => 'AuthController@unlink']);
// API Routes.
Route::get('api/me', ['middleware' => 'auth', 'uses' => 'UserController@getUser']);
Route::put('api/me', ['middleware' => 'auth', 'uses' => 'UserController@updateUser']);

Route::get('/', function () {
     return view('layout', [
         'html' => view('partials.index'),
         'add' => view('partials.add'),
         'partial' => 'index'
         ]);
});

Route::get('/partials/{name}.html', function ($name) {
    $view_path = 'partials.' . $name;
      if (View::exists($view_path)) {
//        return View::make($view_path);
          return view($view_path);
      }
  return abort(404);
});
//
Route::get('hikes/{hike_id}', function($hike_id) {
    $resource = \App\Models\Hike::where('string_id', $hike_id)->with('location','photo_landscape', 'photo_facts','photo_preview','photos_generic')->first()->toJson();

    return view("layout", [
        'html' => view('partials.entry'),
        'add' => view('partials.add'),
        'preload_resource' => '<div data-preload-resource="/api/v1/hikes/'.$hike_id.'">'.$resource.'</div>'
    ]);
});
//
Route::get('/img', function()
{
    $img = Image::make('/tmp/phpgVeDC2/')->resize(300, 200);

    return $img->response('jpg');
});
//
/*
 *  HTML Routes
 */
//Route::group(['prefix' => 'partials'], function() {
//    
//});
/*
 *  API Routes
 */

Route::group(['prefix' => 'api/v1'], function() {
    //Hikes controller
    Route::get('hikes', 'HikesController@index');
    Route::get('hikes/search', 'HikesController@search');
    Route::get('hikes/{hike_id}', 'HikesController@show');
    Route::post('hikes', ['middleware' => 'auth', 'uses' => 'HikesController@store']);
    Route::post('hikes/{hike_id}/photos', ['middleware' => 'auth', 'uses' =>  'HikesController@upload']);
    Route::put('hikes/{hike_id}', ['middleware' => 'auth', 'uses' =>  'HikesController@update']);
    Route::get('reviews', 'HikesController@reviews');
    Route::post('photos/{id}/{type}', ['middleware' => 'auth', 'uses' =>  'PhotoController@delete']);
});

// http://localhost:8000/api/v1/hikes/search?fields=locality,name,photo_facts,string_id&q=rajsko+pruskalo


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
  
/*
 *  All Angular Routes are defined here
 */
$confAloowed = \Config::get('allowedRoutes.routes'); //More dynamic is to get thenm from DB , not from config
$confParams = [];

if (is_array($confAloowed)) {
    foreach ($confAloowed as $route) {
        switch ($route) {
            case 'about':
            case 'add':
            case 'signup':
            case 'login':
            case 'logout':
            case 'profile':
            case 'auth':
            case 'map':
            case 'admin':
                break;
            case 'hikes':
                $confParams[] = \App\Models\Hike::select('string_id')->get()->lists('string_id')->toArray();
                break;
        }
    }
}
//
$actions = array('create', 'edit', 'add','delete','join', 'leave', 'approve','twitter','search');
$confParams = array_flatten($confParams);
$confParams = array_merge($actions, $confParams);
//$confParams = \App\Models\Game::select("slug')->get()->toArray();
//dd($confParams);
//dd($confAloowed);
$action = Request::segment(1);
$param1 = Request::segment(2);
$param2 = Request::segment(3);
$param3 = Request::segment(4);


//
if (!empty($confAloowed) && $action != null) {
    foreach ($confAloowed as $v) {
        /*
         *  where has params (id, lang)
         */
        if (!empty($param1) && in_array($param1, $confParams) && $action == $v) {
// echo 'IF 1  '.$v.' | '.$param1; exit;
            // Route::match(['get','post'], $v . '/' . $param1, function ($v) {
            //     return view("layout", [
            //         'html' => view('partials.entry'),
            //         'add' => view('partials.add'),
            //         'partial' => $v,
            //         'preload_resource' => '<div data-preload-resource="/api/v1/hikes/'.$param1.'">#{resource}</div>'
            //     ]);
            // })->where(['string_id' => '[a-z0-9-_]+']);
        }
        //
        if (!empty($param1) && in_array($param1, $confParams) && $action == $v) {
//            echo 'IF 2  '.$v. ' | '.$param1.' | '.$param2; exit;
            Route::match(['get'], $v . '/' . $param1 . '/' . $param2, function () {
               return view("layout", [
                    'html' => view('partials.entry'),
                    'add' => view('partials.add')
                ]);
            })->where(['string_id' => '[a-z0-9-_]+', 'param' => '[a-z]+'])

                ;
        }
        //
        if (!empty($param1 && $param2 && $param3) && in_array($param1, $confParams) && ctype_digit($param2) && $action == $v) {
// echo 'IF 3  '.$param1.' | '.$param2 .' | '.$param3; exit;
            Route::match(['get'], $v . '/' . $param1 . '/' . $param2 . '/' . $param3, function () {
               return view("layout");
            })->where(['param' => '[a-z]+', 'id' => '[0-9]+', 'string_id' => '[a-z0-9-_]+']);
        }

        Route::match(['get'], $v, function () use($v) {
//            echo File::get(app_path().'/views/index.html');
//            echo '<br>LAST  '.$v; exit;
            switch($v) {
                case 'discover':
                    $part = 'photo_stream';
                    break;
                case 'map':
                    $part = 'map';
                    break;
                case 'about':
                    $part = 'about';
                    break;
                case 'login':
                    $part = 'login-page';
                    break;
                case 'logout':
                    $part = 'logout';
                    break;
                case 'profile':
                    $part = 'profile';
                    break;
                case 'signup':
                    $part = 'signup';
                    break;
                case 'search':
                    $part = 'search';
                    break;
                case 'admin':
                    $part = 'admin';
                    break;
                case 'hikes':
                    $part = 'entry';
                    break;
            }

            return view("layout", [
                    'html' => view('partials.'.$part),
                    'add' => view('partials.add'),
                    'partial' => $part
                ]);
        });
    }
}
