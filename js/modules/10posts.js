function posts() {
  $('.active').addClass('not-active');
  $('.not-active').removeClass('active');
  $('.index').addClass('active');
  $('.list-10-posts').empty();
  let arr = {}

  let html = "";
  $.ajax({
    url: "./yiitest/web/post/ten-posts",
    method: "GET",
    datatype: "json",

    success: function (data) {
      arr = JSON.parse(data);
      // console.log(arr);
      // const user = arr.user;
      const posts = arr;
      //  console.log(posts);
      posts.forEach((value) => {
        // console.log(value)
        html += `<div class='col-md-12 col-xl-12'>
        <div class='blog-entry ftco-animate d-md-flex fadeInUp ftco-animated' >`;
        html += `<div class='text text-2 pl-md-4'>
         <h3 class='mb-2'><a href='post-action.php?post-id=${value.id}'>${value.title}</a></h3>
                 <div class='meta-wrap'>
                     <p class='meta'>`;
        // if (value.user.link) {
        //   html += `<div class='vcard bio'> <img src = 'files-php/uploads/${value.user.link}' width='100px' height = '100px' alt='Image placeholder'> </div>`;
        // }
        html += `<span class='text text-3'>${value.login}</span>
                      <span><i class='icon-calendar mr-2'></i>${value.date}</span>
                    <span><i class='icon-comment2 mr-2'></i> ${value.numberOfComment} Comment</span>
                 </p>
                                </div>
                                 <p class='mb-4'>${value.preview}</p>
                                 <div class='d-flex pt-1  justify-content-between'>
                                     <div>
                                       <a href= "" class='btn-custom link ' data-section="more" data-id=${value.id}>
                                             Подробнее... <span class='ion-ios-arrow-forward'></span></a>
                                    </div>`;
        if (value.user_id === sessionStorage.getItem('id')) {
          html += `<div>
						   <a href='' style='font-size: 1.8em;' class='link'  data-section='fixPost' data-postid= ${value.id} title='Редактировать'>🖍</a>
							   <a href='' class='link'     data-section='remPost'  data-postid= ${value.id} style='font-size: 1.8em;' title='Удалить'>🗑</a>
								</div>`;
        } else if (sessionStorage.getItem('role') === 'admin') {
          html += `<div>
					<a href='' data-section='remPost' class='link' data-postid= ${value.id} style='font-size: 1.8em;' title='Удалить'>🗑</a>
					</div>`;
        }
        html += `</div>
                 </div>
             </div>
         </div>`
      })

      $('.list-10-posts').append(html);
    },
    error: () => {
      console.error("AJAX error");
    }

  });
}


export default posts;