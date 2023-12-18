$(document).ready(function () {
  var cooldownTime = 60; // 冷却时间，单位：秒
  var cooldownInterval;
  var cooldownTimestamp;

  // 从本地存储中获取冷却时间戳
  var storedCooldownTimestamp = localStorage.getItem("cooldownTimestamp");
  if (storedCooldownTimestamp) {
    cooldownTimestamp = parseInt(storedCooldownTimestamp, 10);
    var remainingTime = cooldownTimestamp - Math.floor(Date.now() / 1000);

    if (remainingTime > 0) {
      // 如果剩余时间大于0，则继续冷却
      disableSendButton();
      startCooldownTimer();
    }
  }

  // 邮箱验证码按钮点击事件
  $("#sendVerificationCode").click(function () {
    // 获取邮箱地址
    var email = $("#email").val();

    // 发送请求获取验证码
    $.ajax({
      type: "POST",
      url: "../includes/email/send_verification_code.php", // 替换为发送验证码的PHP文件路径
      data: { email: email },
      success: function (response) {
        // 显示服务器返回的消息
        $("#message").html(
          '<div class="alert alert-info">' + response + "</div>"
        );

        // 设置冷却时间戳
        cooldownTimestamp = Math.floor(Date.now() / 1000) + cooldownTime;

        // 存储冷却时间戳到本地存储
        localStorage.setItem("cooldownTimestamp", cooldownTimestamp);

        // 禁用按钮并开始冷却倒计时
        disableSendButton();
        startCooldownTimer();
      },
      error: function () {
        // 显示验证码发送失败的提示
        $("#message").html(
          '<div class="alert alert-danger">验证码发送失败，请稍后再试。</div>'
        );
      },
    });
  });

  // 监听表单提交事件
  $("#forgetPasswordForm").submit(function (event) {
    event.preventDefault(); // 阻止表单默认提交行为

    // 获取表单数据
    var formData = $(this).serialize();

    // 使用Ajax提交表单数据到服务器
    $.ajax({
      type: "POST",
      url: "../includes/forget/forget_password_process.php", // 替换为处理密码重置的PHP文件路径
      data: formData,
      success: function (response) {
        // 显示服务器返回的消息
        $("#message").html(
          '<div class="alert alert-info">' + response + "</div>"
        );
      },
    });
  });

  function disableSendButton() {
    // 将按钮样式设置为与修改密码按钮相同
    $("#sendVerificationCode")
      .removeClass("btn-secondary")
      .addClass("btn-secondary")
      .prop("disabled", true);
  }

  function enableSendButton() {
    // 将按钮样式设置回原样
    $("#sendVerificationCode")
      .removeClass("btn-secondary")
      .addClass("btn-secondary")
      .prop("disabled", false);
  }

  function startCooldownTimer() {
    // 设置定时器，每秒更新一次冷却时间
    cooldownInterval = setInterval(function () {
      var remainingTime = cooldownTimestamp - Math.floor(Date.now() / 1000);

      if (remainingTime <= 0) {
        // 冷却时间结束，启用按钮，清除定时器
        enableSendButton();
        clearInterval(cooldownInterval);
        $("#cooldownInfo").html(""); // 清空冷却时间显示
        // 清除本地存储中的冷却时间戳
        localStorage.removeItem("cooldownTimestamp");
      } else {
        // 更新冷却时间显示
        $("#cooldownInfo").html("还需等待: " + remainingTime + " 秒");
      }
    }, 1000);
  }
});
