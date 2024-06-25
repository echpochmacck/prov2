function getUsers() {
  $(".active").addClass("not-active");
  $(".not-active").removeClass("active");
  $(".users").addClass("active");
  let str = "<tbody>";
  // url: "./files-php/php-parts/users.php",

  $.ajax({
    url: "./yiitest/web/admin/list-users",
    method: "post",
    datatype: "json",
    success: function (data) {
     
      data.forEach((value) => {
        str += `<tr>
              <th scope='row'>1</th>
              <td>${value["name"]}</td>
              <td>${value["surname"]}</td>
              <td>${value["login"]}</td>
              <td>${value["email"]}</td>"`;
        if (sessionStorage.getItem("id") === value["id"]) {
          str += `<td>
                  себя нельзя блокировать 
                  </td>
                  <td> </td>`;
        } else if (!value["dateUnlock"] || (Date.parse(value["dateUnlock"]) < new Date()) && value["dateUnlock"] !== "1970-01-01 00:00:00") {
          str += `<td>
          <a href = "" data-section='tempblock' data-login=${value["login"]} data-id=${value["id"]} class='btn btn-outline-warning px-4 link'>⏳ Block</a>
          </td>
          <td>
          <a href = "" data-section='foreverblock' data-login=${value["login"]} data-id=${value["id"]} class='btn btn-outline-danger px-4 link'>📌 Block</a>
          </td>`;
        } else if (value["dateUnlock"] === "1970-01-01 00:00:00") {
          str += `<td>
              заблокирован навсегда
              </td><td> </td>`;
        } else {
          str += `<td>
                  заблокирован до ${value["dateUnlock"]}
                  </td><td> </td>`;
        }
        });
        str += '</table>';
    $('thead').after(str);
    },
    error: () => {
      posts();
    },
  });
}

export default getUsers;
