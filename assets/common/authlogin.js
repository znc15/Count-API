// 使用异步请求检查 install.lock 文件内容
fetch("../install/install.lock")
  .then((response) => response.text())
  .then((data) => {
    // 判断 install.lock 文件内容是否为 false
    if (data.trim().toLowerCase() === "false") {
      // 跳转到 index.php
      window.location.href = "../install";
    }
  })
  .catch((error) => {
    // 处理错误
    console.error("Error fetching install.lock:", error);
  });

// 获取 cookie 值的函数
function getCookie(name) {
  var match = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
  if (match) return match[2];
}
// 在你的代码中使用 getCookie 函数
var token = getCookie("token");
// 检查 token 是否存在
if (!token) {
  // 如果存在，执行相应的操作
  // 例如，跳转到 home/index.php
  window.location.href = "../index.php";
}
