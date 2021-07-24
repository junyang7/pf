## 路由约定
01. 如果URI是/api或者以/api/开头，则该URI被划分到api类别中，api类别路由自动添加JSON响应头；
02. 否则URI将会被划分到web或cli类别中；
03. 默认解析的命名空间为\App\Controller；


## 路由语法
```
\Pf\Core\Route::action(string $uri, string $rule, array $extend_list = [])
action:
    method(string $method)
    methodList(array $method_list)
    middleware(string $middleware)
    middlewareList(array $middleware_list)
    namespace(string $namespace)
    prefix(string $prefix)
    group(closure $group)
```


## 基本
### GET
```
Route::get('/', 'Index@index');

/**
 *  uri:        /
 *  method:     GET
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### HEAD
```
Route::head('/', 'Index@index');

/**
 *  uri:        /
 *  method:     HEAD
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### POST
```
Route::post('/', 'Index@index');

/**
 *  uri:        /
 *  method:     POST
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### PUT
```
Route::put('/', 'Index@index');

/**
 *  uri:        /
 *  method:     PUT
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### DELETE
```
Route::delete('/', 'Index@index');

/**
 *  uri:        /
 *  method:     DELETE
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### CONNECT
```
Route::connect('/', 'Index@index');

/**
 *  uri:        /
 *  method:     CONNECT
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### OPTIONS
```
Route::options('/', 'Index@index');

/**
 *  uri:        /
 *  method:     OPTIONS
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### TRACE
```
Route::trace('/', 'Index@index');

/**
 *  uri:        /
 *  method:     TRACE
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### PATCH
```
Route::patch('/', 'Index@index');

/**
 *  uri:        /
 *  method:     PATCH
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### CLI
```
Route::cli('/', 'Index@index');

/**
 *  uri:        /
 *  method:     CLI
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
## 进阶
### method
```
Route::method('GET', /', 'Index@index');

/**
 *  uri:        /
 *  method:     GET
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### methodList
```
Route::methodList(['GET', 'POST', ], /', 'Index@index');

/**
 *  uri:        /
 *  method:     GET,POST
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### middleware
```
Route::middleware('auth')->get(/', 'Index@index');

/**
 *  uri:        /
 *  method:     GET
 *  controller: \App\Controller\Index
 *  action:     index
 *  middleware: middleware['auth', ]
 */
```
### middlewareList
```
Route::middlewareList(['age', 'gender', ])->get(/', 'Index@index');

/**
 *  uri:        /
 *  method:     GET
 *  controller: \App\Controller\Index
 *  action:     index
 *  middleware: middleware['age', 'gender', ]
 */
```
### namespace
```
Route::namespace('User')->get(/', 'Index@index');

/**
 *  uri:        /
 *  method:     GET
 *  controller: \App\Controller\User\Index
 *  action:     index
 */

```
```
Route::namespace('\User')->get(/', 'Index@index');

/**
 *  uri:        /
 *  method:     GET
 *  controller: \User\Index
 *  action:     index
 */
```
### prefix
```
Route::prefix('/srv')->get(/', 'Index@index');

/**
 *  uri:        /srv/
 *  method:     GET
 *  controller: \App\Controller\Index
 *  action:     index
 */
```
### group
```
Route::group(function(){
    Route::get(/a', 'Index@index');
    Route::get(/b', 'Index@index');
});
```
