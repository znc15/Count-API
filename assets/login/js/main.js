// 获取 cookie 值的函数
function getCookie(name) {
  var match = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
  if (match) return match[2];
}
// 在你的代码中使用 getCookie 函数
var token = getCookie("token");
// 检查 token 是否存在
if (token) {
  // 如果存在，执行相应的操作
  // 例如，跳转到 home/index.php
  window.location.href = "../home";
}

$(document).ready(function () {
  $("form").submit(function (event) {
    // 阻止表单默认提交行为
    event.preventDefault();
    // 获取表单数据
    var formData = $(this).serialize();
    // 发送异步请求
    $.ajax({
      type: "POST",
      url: "../includes/login/login_process.php",
      data: formData,
      success: function (response) {
        // 根据后台返回的标识显示相应的提示
        if (response === "CaptchaError") {
          $("#captchaErrorModal").modal("show");
        } else if (response === "PasswordError") {
          $("#passwordErrorModal").modal("show");
        } else if (response === "UserNotFound") {
          $("#userNotFoundErrorModal").modal("show");
        } else if (response === "Success") {
          // 登录成功，直接跳转到管理面板
          window.location.replace("index.php");
        }
      },
    });
  });
  // 添加模态框完全关闭后的事件处理
  $(".modal").on("hidden.bs.modal", function () {});
});
