// import createMenu from "./modules/menu.js";

import { regForm, getReg } from "./modules/reg-form.js"
import posts from './modules/10posts.js';
import deletePost from './modules/delPost.js';
import allPosts from './modules/allPosts.js';
import { logForm, loginn } from "./modules/log-form.js";
import activeMenu from "./modules/activeMenu.js";
import logout from "./modules/logout.js";
import postAction from "./modules/post-action-form.js";
import postContent from "./modules/postContent.js";
import commentList from "./modules/comments.js";
import commentForm from "./modules/commentForm.js";
import deleteComment from "./modules/delComment.js";
import getUsers from "./modules/users.js";
import tempBlockForm from "./modules/tempblock.js";
import foreverBlockForm from "./modules/foreverblock.js";


function clearForm() {
    $('form').each(function () {
        $(this).find('input[type="text"], textarea').val('');
    });
}


function clearUrl() {
    history.replaceState(null, '', location.pathname);
}
$(() => {




    // window.onbeforeunload = () => {
    //     logout();
    // };


    $('.reg-form').on('submit', function (e) {
        e.preventDefault();
        console.log('dsad');
        getReg();
    });

    $(".log-form").on("submit", function (e) {
        e.preventDefault();
        loginn();
    });


    let url;
    clearUrl();
    posts();
    // createMenu()
    if (sessionStorage.getItem('token')) {
        $('.identity_user').addClass('not-active');
        $('.exting_user').removeClass('not-active');
    }

    if (sessionStorage.getItem("role") === "admin") {
        $(".admin").removeClass("not-active");
    }


    $('body').on('click', '.link', function (e) {
        e.preventDefault();
        clearUrl();

        activeMenu($(this));
        // console.log($(this));
        switch ($(this).data('section')) {
            case 'index':
                // console.log(';')
                posts();
                break;
            case 'register':
                regForm();
                clearForm();
                break;
            case 'login':
                logForm();
                clearForm();
                break;
            case 'blogs':
                // console.log('dsa');
                allPosts();
                break;
            case 'exting':
                // console.log('выход');
                logout();
                break;

            case 'post-action':
                // console.log('создать');
                postAction();
                break;
            case 'more':
                postContent($(this).data('id'));
                break;
            case 'reply':
                commentForm($(this), $(this).data('postid'), $(this).data('commentid'));
                console.log('ответ');
                break;
            case 'remPost':
                // console.log('удалииииить');
                deletePost(sessionStorage.getItem('id'), $(this).data('postid'), sessionStorage.getItem('role'));
                break;
            case 'fixPost':
                // console.log('редакттирование');
                if (sessionStorage.getItem('role') === 'avtor') {
                    postAction($(this).data('postid'));
                }
                break;
            case 'deletecomment':
                // console.log($(this).data('id'));
                deleteComment($(this).data('id'));
                postContent($(this).data('postid'))
                break;
            case 'users':
                // console.log('users');
                getUsers();
                break;
            case 'tempblock':
                // console.log('временная блокировка');
                tempBlockForm($(this).data('id'));
                $(function () {
                    $('#date-block').daterangepicker({
                        singleDatePicker: true,
                        showDropdowns: true,
                        timePicker: true,
                        timePicker24Hour: true,
                        minYear: 2023,
                        maxYear: parseInt(moment().format('YYYY'), 10),
                        locale: {
                            format: 'DD.MM.YYYY HH:mm'
                        }
                    });
                });
                $('#date-block').on('apply.daterangepicker', function (ev, picker) {
                    $(this).val(picker.startDate.format('DD.MM.YYYY HH:mm'))
                });
                break;
            case 'foreverblock':
                console.log('навсегда');
                foreverBlockForm($(this).data('id'));
                break;
            case 'pages':
                // console.log('adsdad')
                url = new URL(window.location);

                console.log($(this).data('offset'));
                url.searchParams.set('page', $(this).data('offset') + 1); // Устанавливаем новое значение параметра
                // page = 1;
                window.history.pushState({}, '', url);
                allPosts();
                break;
            case 'arrow':
                if ($(this).data('count') == $(this).data('offset')) {
                    clearUrl();
                    allPosts();
                } else {
                    url = new URL(window.location);
                    // console.log($(this).data('offset'));
                    url.searchParams.set('page', $(this).data('offset') + 1); // Устанавливаем новое значение параметра
                    // page = 1;
                    window.history.pushState({}, '', url);
                    allPosts();
                }
                break;
        }

    });





});
