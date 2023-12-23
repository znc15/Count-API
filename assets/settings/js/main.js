function changePassword() {
  $.ajax({
    url: "change_password.php",
    method: "POST",
    data: $("#changePasswordForm").serialize(),
    dataType: "json",
    success: function (response) {
      $("#resultModal .modal-body").html(response.message);
      $("#resultModal").modal("show");
    },
    error: function () {
      alert("错误：无法与服务器通信。");
    },
  });
}
function refreshPage() {
  location.reload();
}
