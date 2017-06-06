<?php
Route::get('/work/more',function(){

});
Route::get('/previewimg/',function(){
    \MM\PreviewImage::getImage();
});
Route::controller('/','IndexController');
Route::controller('/index','IndexController');

