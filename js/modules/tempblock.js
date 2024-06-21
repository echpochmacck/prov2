import getUsers from "./users.js";

function tempBlockForm(userId) {
  $(".active").addClass("not-active");
  $(".not-active").removeClass("active");
  $(".temp-block-form").addClass("active");
  $(".tempblockform").on("submit", function (e) {
    e.preventDefault();
    // получение данных с формы
    let formData = $(".tempblockform")[0];
    formData = new FormData(formData);
    formData.append("user_id", userId);
    console.log(formData);
    $.ajax({
      type: "POST",
      url: "./files-php/php-parts/temp-block.php",
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

export default tempBlockForm;
