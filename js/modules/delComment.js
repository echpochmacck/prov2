function deleteComment(commentId)
{
    if (commentId) {
        $.ajax({
            type: "get",
            url: "./files-php/php-parts/delete-comment.php",    
            data: {'comment_id': commentId},
            // dataType: "dataType",
            success: function (response) {
                console.log(response);
                
            }
        });
    }
}

export default deleteComment;