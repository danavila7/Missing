$(document).ready(function ($){

  yepnope({
  test : $('body').hasClass('home'),
  yep  : [
    // JS
    '/public/js/jquery.js',      
    '/public/js/modernizr.js',
    '/public/bootstrap-3.2.0/css/bootstrap.css',

    ] 
  });
 });