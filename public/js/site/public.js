url = $('body').attr('site-Homeurl');
image_empty_player = '/images/full-shirt.png';
body_user_key='';
login_url='';
login_register='';
body_lang='ar';
val_whda='M ';
point_whda='pt';

start_limit_match = 1;
pub_limit_match = 1;
limit_list_player = 10;
$(document).ready(function () {
    body_user_key = $('body').attr('data-user');
    login_url = url + '/login';
    login_register = url + '/register';
    //    loader = '';
    //    host = window.location.hostname;
    body_lang = $('body').attr('data-homelang'); //ar or en ....
    
});

