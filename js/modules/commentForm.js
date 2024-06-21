import commentList from "./comments.js";

function commentForm(selector, postId, commentId ='') {
    // console.log(selector);
    $('.comment-form-wrap').remove();
    $(selector).after(`
<div class="comment-form-wrap pt-5">
    <h3 class="mb-5">Оставьте комментарий</h3>
    <form action="#" class="p-3 p-md-5 bg-light comment-form" method="POST">
        <div class="form-group">
            <label for="message">Сообщение</label>
            <textarea name="message" id="message" cols="30" rows="10" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <input type="submit" value="Отправить" name="send_comment" class="btn py-3 px-4 btn-primary">
        </div>
    </form>
</div>
</div>`);

    $('.comment-form').on('submit', (e) => {
        e.preventDefault();
        console.log('коммент');
        const formData = new FormData($('.comment-form')[0]);

        if (commentId) {
            formData.append('comment_id', commentId);
        }

        formData.append('post_id', postId);
        formData.append('user_id', sessionStorage.getItem('id'));
        console.log(formData);
        // console.log(window.location.href);
        $.ajax({
            type: "POST",
            url: "./files-php/php-parts/createComment.php",
            data: formData, 
            processData: false,
            contentType: false,
            // dataType: "dataType",
            success: function (response) {
                console.log(response);
                commentList(postId);
            }
        });
    });
}
export default commentForm;