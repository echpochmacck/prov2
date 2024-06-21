import posts from "./10posts.js";
function regForm (){
			// $('active').addClass('non-active');
			$('.active').addClass('not-active');
			$('.not-active').removeClass('active');
			// console.log('sdd')
			$('.register').addClass('active');
			$('.register').removeClass('not-active');
		}
function getReg() {
	const formData = new FormData($('.reg-form')[0]);
	console.log(formData);
	$.ajax({
		type: "POST",
		url: "./files-php/php-parts/register-form.php", 
		processData: false,
		contentType: false,
		data: formData,
		success: (response) => {
			// console.log(response)
			const obj = $.parseJSON(response);
			if (obj.token) {
				sessionStorage.setItem('userToken', obj.token);
				sessionStorage.setItem('role', obj.role);
				sessionStorage.setItem('id', obj.userId);
				$('.identity_user').addClass('not-active');
				$('.exting_user').removeClass('not-active');
				posts();
			} else if(obj.errors) {
				const errors = obj.errors;
				console.log(errors);
				$($('.reg-form')[0]).find(':input').each(function (key, value) { 
					if (errors[$(value).attr('name')+'Message']) {
						// console.log(value);
						const div = `<div id="error">
							${errors[$(value).attr('name')+'Message']}
						</div>`
						$(value).next('#error').remove();
						$(value).after(div);

					}  
				});
			}
		},
		
		error:  () => {
			console.log('не норм')
		}
	});
}

export {regForm, getReg};