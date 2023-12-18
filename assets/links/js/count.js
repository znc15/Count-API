$(document).ready(function () {
  $("#addLinkForm").submit(function (e) {
    e.preventDefault();

    $.ajax({
      url: "../../includes/links/record_visit.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function (response) {
        // 通过 console.log 输出服务器返回的响应，以进行调试
        console.log("服务器响应：", response);

        // 根据成功或失败显示相应的 modal
        if (response.success) {
          $("#successModal").modal("show");
          // 点击确定按钮后刷新页面
          $("#successModal").on("hidden.bs.modal", function () {
            location.reload();
          });
        } else {
          $("#errorDetails").text(response.error || "发生未知错误，请重试。");
          $("#errorModal").modal("show");
          // 点击确定按钮后刷新页面
          $("#errorModal").on("hidden.bs.modal", function () {
            location.reload();
          });
        }
      },
      error: function (xhr, status, error) {
        console.error("Ajax请求出错：", xhr, status, error);

        // 显示错误的 modal
        $("#errorDetails").text("发生未知错误，请重试。");
        $("#errorModal").modal("show");
        // 点击确定按钮后刷新页面
        $("#errorModal").on("hidden.bs.modal", function () {
          location.reload();
        });
      },
    });
  });
});
