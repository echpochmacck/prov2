import posts from "./10posts.js";
import commentList from "./comments.js";
function postContent(postId) {
    $('.post-content').empty();
    $('.active').addClass('not-active');
    $('.not-active').removeClass('active');
    $('.post').addClass('active');
    // console.log(postId);
    $.ajax({
        type: "GET",
        url: "./files-php/php-parts/post-content.php",
        data: { postId: postId },
        dataType: "json",
        success: function (response) {
            // console.log(response);
            if (response) {
                let html = ` <div class="post">
            <h1 class="mb-3">${response.title}</h1>
            <div class="meta-wrap">
                <p class="meta">
                    <!-- <img src='avatar.jpg' /> -->
                    <span class="text text-3">
                        <!-- << /span> -->
                        <span><i class="icon-calendar mr-2"></i>${response.date}</span>
                        <span><i class="icon-comment2 mr-2"></i> ${response.numberOfComment}
                            Comment</span>
                </p>
            </div>
            <p>
                ${response.content}
            </p>`;
                if (response.link) {
                    html += `
            <div><img src="./uploads/${response.link}" alt="#" width='200px'
                    height="300px"></div>
            `}
                if (sessionStorage.getItem('role') == 'avtor' && sessionStorage.getItem('id') == response.user_id) {
                    html += ` <div>
           <a href='' style='font-size: 1.8em;' class='link'  data-section='fixPost' data-postid= ${response['post_id']} title='Редактировать'>🖍</a>
					 <a href='' class='link'     data-section='remPost'  data-postid= ${response['post_id']} style='font-size: 1.8em;' title='Удалить'>🗑</a>
            </div>`;
                } else if (sessionStorage.getItem('role') === 'admin') {
                    html += ` <div> <a href='' class='link' data-section='remPost'  data-postid= ${response['post_id']} style='font-size: 1.8em;' title='Удалить'>🗑</a> </div>`
                }
                html += `</div>`;
                $('.post-content').append(html);
                commentList(postId);
            } else {
                posts();
            }
        }
    });
}

export default postContent;