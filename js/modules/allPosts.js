import posts from "./10posts.js";
function allPosts() {

	$('.active').addClass('not-active');
	$('.not-active').removeClass('active');
	$('.blogs').addClass('active');
	$('.list-posts').empty();
	if (sessionStorage.getItem('role') == 'avtor') {
		console.log('testbog')
		$('.div-creat-post').removeClass('not-active');
	} else {
		console.log()
		$('.div-creat-post').addClass('not-active');
	}
	let arr = {}
	let html = "";
	
	console.log(new URL(window.location.href));
	
	const url = new URL(window.location);
	const params = new URLSearchParams(url.search);
	let page;
	let offset;
	if (params.has('page')) {
		// console.log('dsad');
		page = +params.get('page')
		offset = +page - 1;
	} else {
		url.searchParams.set('page', 1); // Устанавливаем новое значение параметра
		page = 1;
		window.history.pushState({}, '', url);
		offset = 0;
	}
	// console.log('страниуа',page);
	const data = {};
	// console.log(offset);
	data['offset'] = offset;

	// url: "./files-php/php-parts/allPosts.php",
	$.ajax({
		url: "./yiitest/web/post/all-posts",
		method: "GET",
		data : data,
		datatype: "json",

		success: function (data) {
			if (data) {
			arr = data;
			// console.log(arr);
			const user = arr.user;
			const posts = arr.posts;
			let pages = arr.pages;
			posts.forEach((value) => {
				// console.log(value)
				html += `<div class='col-md-12 col-xl-12'>
				<div class='blog-entry ftco-animate d-md-flex fadeInUp ftco-animated' >`;
				html += `<div class='text text-2 pl-md-4'>
				<h3 class='mb-2'><a href='post-action.php?post-id=${value.id}'>${value.title}</a></h3>
				   <div class='meta-wrap'>
					   <p class='meta'>`;
				// if (value['user.link']) {
				// 	html += `<div class='vcard bio'> <img src = 'files-php/uploads/${value['user.link']}' width='100px' height = '100px' alt='Image placeholder'> </div>`;
				// }
				html += `<span class='text text-3'>${value.login}</span>
						<span><i class='icon-calendar mr-2'></i>${value.date}</span>
					  <span><i class='icon-comment2 mr-2'></i> ${value.numberOfComments} Comment</span>
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
					</div>`

				}
				html += `</div>
				   </div>
			   </div>
		   </div>`;

			})
				// if (!sessionStorage.getItem('page')) {
				// 	// console.log('asdasda')
				// 	page = 1;
				// 	sessionStorage.setItem('page', page);
				// } else {
				// 	page = sessionStorage.getItem('page');
				// }
				html += `
			<div class="col">
				<div class="block-27">
					<ul>`;
					if (page > 1) {
					html += `<li><a href="" class="link" data-section='arrow' data-offset=${offset-1} data-count=${arr.count}>&lt;</a></li>`
				  
					} 
				pages.forEach(value => {
					// console.log(page, value.page);
					if (page == value.page) {
						html += `<li class = 'active '><a href="" class="link pages" data-section=pages data-offset=${value.page - 1}</a>${value.page}</a></li>`;
					} else {
						html += `<li><a href="" class="link pages" data-section=pages data-offset=${value.page - 1}>${value.page}</a></li>`
					}
				});
				html += `<li><a href="" class="link" data-section='arrow' data-offset=${offset+1} data-count=${arr.count}>&gt;</a></li>
				   </ul>
				   </div>
				   </div>
				   </div>`;

			// 	<li><a href="">&lt;</a></li>
			// 	<li><a href=""></a></li>
			//    	<li><a href=""></a></li>
			// 	<li><a href="">&gt;</a></li>
			//    </ul>
			//    </div>
			//    </div>
			//    </div>`;

			$('.list-posts').append(html);
			// $('.col').on('click', 'a', (e) => {
			// 	e.preventDefault();
			// 	console.log($(this));
			// 	sessionStorage.setItem('page', $(this).text());
			// });
		} else {
			// posts();
		}
		},
		error: () => {
			console.error("AJAX error");

		}


	});
}


export default allPosts;