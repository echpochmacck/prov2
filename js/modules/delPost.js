import posts from "./10posts.js";

function deletePost (user_id, postId, role) {
    const data = {};
    data.user_id = user_id;
    data.post_id = postId;
    data.role = role;
    // "./yiitest/web/post/ten-posts",
    // ./files-php/php-parts/deletePost.php
    $.ajax({
        type: "POST",
        url: "./yiitest/web/post/delete-post",
        data: data,
        dataType: "json",
        success: function (response) {
            posts();
        }
    });
}

export default deletePost;