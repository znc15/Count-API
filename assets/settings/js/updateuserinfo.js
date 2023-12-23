//修改用户名和修改邮箱
function updateUserInfo() {
  // 获取用户输入的新用户名和新邮箱
  var newUsername = document.getElementById("newUsername").value;
  var newEmail = document.getElementById("newEmail").value;

  // 创建一个FormData对象，用于将数据发送到服务器
  var formData = new FormData();
  formData.append("newUsername", newUsername);
  formData.append("newEmail", newEmail);

  // 发送POST请求到update_info.php
  fetch("update_info.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((data) => {
      // 使用Bootstrap模态框显示成功或失败消息
      var modalContent = document.getElementById("modalContent");
      modalContent.innerHTML = data;
      $("#myModal").modal("show");
    })
    .catch((error) => console.error("Error:", error));
}

function refreshPage() {
  location.reload(true); // 刷新页面
}
