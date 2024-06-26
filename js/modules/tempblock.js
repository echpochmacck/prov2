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
    // url: "./yiitest/web/admin/temp-block",
   
    $.ajax({
      type: "POST",
      url: "./yiitest/web/admin/temp-block",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        console.log(response);
       
        if (!response['error']) {
          getUsers()
        } else {
          alert(response['error'])
        }
       
      },
      error: () => {
        console.log("nenorm");
      },
    });
  });
}

export default tempBlockForm;
