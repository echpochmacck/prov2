import posts from './10posts.js';
import postContent from './postContent.js'
function postAction(postId = '') {
    console.log(postId);
    $('.active').addClass('not-active');
    $('.not-active').removeClass('active');
    $('.post-action').addClass('active');


    $('.post-action-form input[name="title"]').val('');
    $('.post-action-form input[name="preview"]').val('');
    $('.post-action-form textarea[name="content"]').val("");
    if (postId) {
        $.ajax({
            type: "get",
            url:  "./yiitest/web/post/create-post",
            data: { postId: postId },
            dataType: "json",
            success: (response) => {
                console.log(response);
                $('.post-action-form input[name="title"]').val(response.title);
                $('.post-action-form input[name="preview"]').val(response.preview);
                $('.post-action-form textarea[name="content"]').val(response.content);


            }
        });
    }

    $('.post-action-form').off('submit');
    $('.post-action-form').on('submit', (e) => {
        e.preventDefault();
        console.log('testACtion');
        const formData = new FormData($('.post-action-form')[0]);
        formData.append('user_id', sessionStorage.getItem('id'));
        if (postId) {
            formData.append('post_id', postId);
        }
        console.log(formData);
        $.ajax({
            type: "POST",
            url: "./yiitest/web/post/create-post",
            processData: false,
            contentType: false,
            data: formData,
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response['errors']) {
                    alert('гдето ошибка');
                } else {

                    posts();
                }
            }
        });
    });
}

export default postAction;