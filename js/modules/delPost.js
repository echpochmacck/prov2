import posts from "./10posts.js";

function deletePost (user_id, postId, role) {
    const data = {};
    data.user_id = user_id;
    data.postId = postId;
    data.role = role;
    $.ajax({
        type: "POST",
        url: "./files-php/php-parts/deletePost.php",
        data: data,
        dataType: "json",
        success: function (response) {
            posts();
        }
    });
}

export default deletePost;