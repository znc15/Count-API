$(document).ready(function () {
  $("#addLinkForm").submit(function (e) {
    e.preventDefault();

    $.ajax({
      url: "../includes/links/record_visit.php",
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

// 点击添加监测链接按钮时触发的事件
$("#addLinkModal").on("show.bs.modal", function () {
  // 发送 Ajax 请求获取国家数据
  $.ajax({
    url: "../includes/links/get_countries.php",
    type: "GET",
    dataType: "json", // 明确指定返回的数据类型为 JSON
    success: function (data) {
      // 清空下拉选项
      $("#region").empty();

      // 将获取的国家数据添加到下拉选项中
      $.each(data, function (key, value) {
        $("#region").append(
          '<option value="' + value.code + '">' + value.name + "</option>"
        );
      });
    },
    error: function (error) {
      alert("获取国家数据失败！");
    },
  });
});

// 点击查看链接
$(document).on("click", ".view-link-btn", function () {
  var url = $(this).data("url");

  // 发送 AJAX 请求获取链接信息
  $.ajax({
    url: "../includes/links/get_link_info.php",
    type: "post",
    data: { url: url },
    success: function (data) {
      var linkInfo = JSON.parse(data);

      // 更新 Modal 内容
      $("#viewUrl").text(linkInfo.url);
      $("#urlToken").text(linkInfo.urltoken);
      $("#totalVisitCount").text(linkInfo.totalVisitCount);
      $("#todayVisitCount").text(linkInfo.todayVisitCount);
      $("#createTime").text(linkInfo.createTime);

      // 显示 Modal
      $("#viewLinkModal").modal("show");
    },
    error: function (error) {
      alert("获取链接信息失败！");
    },
  });
});
//复制js代码
document.addEventListener("DOMContentLoaded", function () {
  var clipboardBtn = document.getElementById("btnjs");

  if (clipboardBtn) {
    clipboardBtn.addEventListener("click", function () {
      // 获取当前选择的链接的urltoken
      var urlToken = $("#urlToken").text();

      // 构建嵌入代码
      var embedCode =
        '<script src="<?php echo $siteUrl; ?>storage/monitor_script_' +
        urlToken +
        '.js"></script>';

      // 创建弹窗
      var modal = document.createElement("div");
      modal.className = "modal fade";
      modal.id = "myModal";
      modal.setAttribute("tabindex", "-1");
      modal.setAttribute("role", "dialog");
      modal.innerHTML = `
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">复制成功</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <p>代码已成功复制，请粘贴到你的网页中。</p>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-primary" data-dismiss="modal">确定</button>
              </div>
          </div>
      </div>
  `;

      // 显示弹窗
      document.body.appendChild(modal);
      $("#myModal").modal("show");

      // 复制代码到剪贴板
      navigator.clipboard
        .writeText(embedCode)
        .then(function () {
          // 复制成功后的操作
        })
        .catch(function (err) {
          console.error("复制失败：", err);
        });
    });
  } else {
    console.error('找不到具有ID "btnjs" 的按钮元素。');
  }
});

// 点击删除链接
$(document).on("click", ".delete-link", function () {
  var url = $(this).data("url");

  // 设置删除按钮的链接
  $("#deleteLinkBtn").data("url", url);

  // 显示确认删除的 Modal
  $("#confirmDeleteModal").modal("show");
});

$("#deleteLinkBtn").on("click", function () {
  console.log("Delete button clicked!");
  var url = $(this).data("url");

  // 发送 AJAX 请求删除链接
  $.ajax({
    url: "../includes/links/delete_link.php",
    type: "post",
    data: { url: url },
    success: function (data) {
      // 关闭确认删除的 Modal
      $("#confirmDeleteModal").modal("hide");

      // 刷新页面或更新列表等操作
      location.reload(); // 可根据需要进行更改
    },
    error: function (error) {
      alert("删除链接失败！");
    },
  });
});
