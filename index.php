<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ====================== CORS HEADERS ======================
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // For development: allow the specific frontend origin
    header("Access-Control-Allow-Origin: http://localhost:8082");
    
    // Alternative (less secure, but convenient for local dev with multiple ports/tools):
    // header("Access-Control-Allow-Origin: *");
}

// Allow the methods your API uses
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow common headers (very important if you're sending JSON or Authorization)
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");

// Allow credentials if you need cookies/auth (optional)
header("Access-Control-Allow-Credentials: true");

// Handle preflight OPTIONS requests (browser sends these automatically)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);  // or 204 No Content
    exit(0);
}
// ========================================================

require_once($_SERVER['DOCUMENT_ROOT'].'/route.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/controllers/ControllerGrupos.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/controllers/ControllerContatos.php');


//#########################Grupos####################################
Route::add('/grupos',function(){
   if(true)(new ControllerGrupos())->find();
},'get');

Route::add('/grupos/([0-9]*)',function($id){
   if(true)((new ControllerGrupos())->findById($id));
},'get');

Route::add('/grupos',function(){
    if(true)((new ControllerGrupos())->create());
},'post');

Route::add('/grupos/([0-9]*)',function($id){
    if(true)((new ControllerGrupos())->update($id));
},'put');

Route::add('/grupos/([0-9]*)',function($id){
    if(true)((new ControllerGrupos())->del($id));
},'delete');
//#########################Grupos####################################


//#########################Contatos####################################
Route::add('/contatos',function(){
   if(true)(new ControllerContatos())->find();
},'get');

Route::add('/contatos/([0-9]*)',function($id){
   if(true)((new ControllerContatos())->findById($id));
},'get');

Route::add('/contatos',function(){
    if(true)((new ControllerContatos())->create());
},'post');

Route::add('/contatos/([0-9]*)',function($id){
    if(true)((new ControllerContatos())->update($id));
},'put');

Route::add('/contatos/([0-9]*)',function($id){
    if(true)((new ControllerContatos())->del($id));
},'delete');
//#########################Contatos####################################


Route::add('/(.*)',function(){
    require_once($_SERVER['DOCUMENT_ROOT'].'/404.php');
},'get');

Route::run('/');

