$(document).ready(function () {
  // 检查是否存在 token
  var token = getCookie("token");

  if (token) {
    // 如果存在 token，直接跳转到 home/index.php
    window.location.href = "home/index.php";
  }
});

// 获取指定名称的 cookie 值
function getCookie(name) {
  var cookies = document.cookie.split(";");

  for (var i = 0; i < cookies.length; i++) {
    var cookie = cookies[i].trim();

    // 检查是否以指定名称开头
    if (cookie.startsWith(name + "=")) {
      return cookie.substring(name.length + 1);
    }
  }

  return null;
}
