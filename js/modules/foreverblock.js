import getUsers from "./users.js";

function foreverBlockForm(user_id) {
    console.log('привет')
    $(".active").addClass("not-active");
    $(".not-active").removeClass("active");
    $('.forever-block').addClass("active");
    $(".forever-block-form").on("submit", function (e) {
    e.preventDefault();
    // получение данных с формы
    let formData = $(".forever-block-form")[0];
    formData = new FormData(formData);
    formData.append("user_id", user_id);
    formData.append('admin_id', sessionStorage.getItem('id'))
    console.log(formData);
    $.ajax({
      type: "POST",
      url: "./files-php/php-parts/forever-block.php",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        console.log(response);
        getUsers()
       
      },
      error: () => {
        console.log("nenorm");
      },
    });
  });
}

export default foreverBlockForm;
