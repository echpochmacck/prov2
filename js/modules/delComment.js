function deleteComment(commentId)
{
    // ./yiitest/web/post/delete-post
    // url: "./files-php/php-parts/delete-comment.php",    
    if (commentId) {
        $.ajax({
            type: "POST",
            url: "./yiitest/web/comment/delete-comment",    
            data: {'comment_id': commentId},
            // dataType: "dataType",
            success: function (response) {
                console.log(response);
                
            }
        });
    }
}

export default deleteComment;