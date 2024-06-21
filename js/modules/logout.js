import posts from "./10posts.js";
function logout() {
    const id = sessionStorage.getItem('id');
    $.ajax({
        type: "post",
        url: "./files-php/init/init-logout.php",
        data: { id: id },
        dataType: "json",
        success: function (response) {
            const json = $.parseJSON(response);
            if (json) {
                sessionStorage.clear();
                posts();
                $('.exting_user').addClass('not-active');
                $('.identity_user').removeClass('not-active');
                $('.admin').addClass('not-active');
            }
        }
    });
}

export default logout;