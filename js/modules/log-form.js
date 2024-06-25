import posts from "./10posts.js";
function logForm() {
  // sessionStorage.setItem("key", "asASsawew");
  // $('.container').append(form);
  $(".active").addClass("not-active");
  $(".not-active").removeClass("active");
  // console.log('sdd')
  $(".login").addClass("active");
  $(".login").removeClass("not-active");


  
}

function loginn() {
  let formData = $(".log-form")[0];
  formData = new FormData(formData);
  formData = Object.fromEntries(formData);
  // перевод в json
  // formData = JSON.stringify(formData);
  console.log(formData);
  // запрос
  $.ajax({
    type: "POST",
    url: "./yiitest/web/user/login",
    data: formData,
    // contentType: "application/json",
    // dataType: "json",
    success: function (response) {
      // console.log(response);
      const obj =response ;
      console.log(obj);
      if (obj.token) {
        // console.log(obj['dateUnlock'])
        if (Date.parse(obj["dateUnlock"]) == Date.parse("1970-01-01 00:00:00")) {
          $(".log").after("вы забанены навсегда  😪");
        }  else if (Date.parse(obj["dateUnlock"])> new Date()) {

          $(".log").after(`вы забанены до ${obj["dateUnlock"]}`);
        }else {
          sessionStorage.setItem("token", obj.token);
          // console.log(Date.parse(obj["dateUnlock"])>new Date());
          sessionStorage.setItem("role", obj.role);
          sessionStorage.setItem("id", obj.id);
          $(".identity_user").addClass("not-active");
          $(".exting_user").removeClass("not-active");
          if (sessionStorage.getItem("role") === "admin") {
            $(".admin").removeClass("not-active");
          }
          posts();
        }
        // console
        //     posts();
        // } else {
        //     $('.invalid-feedback').text(obj.error);
      } else {
        console.log(obj.error);
        $('.invalid-feedback', '.log-form').remove();
        $('#login', '.log-form').after(`<div class='invalid-feedback'>${obj.error}</div>`);
      }
    },
    error: () => {
      console.log("nenorm");
    },
  });
}
export {logForm, loginn};
