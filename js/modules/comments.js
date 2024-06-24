import commentForm from "./commentForm.js";
function commentList(id) {

    let html = `<div class="comments pt-5 mt-5">
    <h3 class="mb-5 font-weight-bold">комментариев</h3>
    <ul class="comment-list">`;


    // "./yiitest/web/comment/list-comment"
    // ./files-php/php-parts/comments.php
    $.ajax({
        type: "GET",
        url: "./yiitest/web/comment/list-comment",
        data: { post_id: id },
        dataType: "json",
        success: function (response) {
            console.log(response);
            // let html = '';
            html = probeg2(response, html);
            // console.log(html)
            $('.comments').remove();
            $('.comment-form').remove();
            $('.post-content').append(html);
            if (sessionStorage.getItem('role')=='avtor') {

                commentForm('.comments', id);
            }


        }
    });
}


function probeg2(obj, html = '', lvl = 0) {
    
    for (let value in obj) {
        html += `<li class='comment' ${lvl ? `style='margin-left:${lvl * 100}px'` : ''}>`;
        if (obj[value]['com']['link']) {
            html += `<div class='vcard bio'>
            <img src='./uploads/${obj[value]['com']['link']}' alt='#'>
        </div>`;
        }
        html += `<div class='comment-body'>
        <div class='d-flex justify-content-between '><h3>
        ${obj[value]['com']['login']} ${lvl}</h3>`;
        
        
        html += ` </div> <div class='meta'>${obj[value]['com']['date']}</div>
        <p>
        ${obj[value]['com']['message']}
        </p>`;
        
        if (sessionStorage.getItem('role') === 'admin') {
            html += `<a href='' class='text-danger link' data-section='deletecomment' data-id =${obj[value]['com']['id']} data-postid=${obj[value]['com']['post_id']} style='font-size: 1.8em;'' title='Удалить'>🗑</a>`;
        }
        if ( sessionStorage.getItem('role') === 'avtor' ) {
            html += `<p><a href='' class='reply link' data-section='reply' data-commentid=${obj[value]['com']['id']} data-postid=${obj[value]['com']['post_id']}>Ответить</a></p>`;
            }
          html += `</div> </li>`;
        //   console.log(`объект номер ${obj[value]}`,obj[value]['answer'])
        // console.log('ответ', obj[value]['answer']);
          if (obj[value]['answer']) {
            // lvl++
            html = probeg2(obj[value]['answer'], html, lvl + 1);
        }

    }
    // html += '</ul>'
    return html
}



export default commentList