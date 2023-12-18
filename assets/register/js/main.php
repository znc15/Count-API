$(document).ready(function () {
    var remainingTime = <?php
    echo isset($_SESSION['lastSentTime']) ? max(60 - (time() - $_SESSION['lastSentTime']), 0) : 0;
?>;
    var intervalId;

    // 检查本地存储中是否有弹窗时间戳
    var popupTime = localStorage.getItem('popupTime');
    if (popupTime) {
        var currentTime = Math.floor(Date.now() / 1000);
        var timeDifference = currentTime - popupTime;

        if (timeDifference < 60 && timeDifference > 0) {
            remainingTime = 60 - timeDifference;

            // 显示剩余等待时间
            updateButtonText();
            intervalId = setInterval(function () {
                remainingTime--;

                if (remainingTime <= 0) {
                    clearInterval(intervalId);
                    updateButtonText(); // 还原按钮文字
                } else {
                    updateButtonText(); // 更新按钮文字
                }
            }, 1000);
        }

        // 清除本地存储的弹窗时间戳
        localStorage.removeItem('popupTime');
    } else if (remainingTime > 0) {
        // 如果没有弹窗时间戳但 remainingTime 大于 0，表示不是第一次加载页面
        // 显示剩余等待时间
        updateButtonText();
        intervalId = setInterval(function () {
            remainingTime--;

            if (remainingTime <= 0) {
                clearInterval(intervalId);
                updateButtonText(); // 还原按钮文字
            } else {
                updateButtonText(); // 更新按钮文字
            }
        }, 1000);
    }

    // 获取验证码
    $("#generateCode").click(function () {
        if (remainingTime > 0) {
            alert("请等待 " + remainingTime + " 秒后再次获取验证码。");
            return;
        }

        var email = $("input[name='email']").val();

        // 记录弹窗时间戳到 localStorage
        localStorage.setItem('popupTime', Math.floor(Date.now() / 1000));

        // 在这里调用后台接口发送邮件，包含生成的验证码
        // 这里使用ajax请求模拟，实际中需要通过后台发送邮件
        $.ajax({
            url: '../includes/email/send_verification_code.php', // 替换为实际的后台处理文件
            type: 'POST',
            data: { email: email },
            success: function (response) {
                alert(response);

                // 清除 localStorage 中的弹窗时间戳
                localStorage.removeItem('popupTime');

                // 显示倒计时
                remainingTime = 60; // 设置等待时间为60秒
                updateButtonText();

                intervalId = setInterval(function () {
                    remainingTime--;

                    if (remainingTime <= 0) {
                        clearInterval(intervalId);
                        updateButtonText(); // 还原按钮文字
                    } else {
                        updateButtonText(); // 更新按钮文字
                    }
                }, 1000);
            },
            error: function (error) {
                alert('验证码发送失败');
            }
        });

        // 更新按钮文字
        function updateButtonText() {
            if (remainingTime > 0) {
                $("#generateCode").prop("disabled", true); // 禁用按钮
                $("#remainingTimeInfo").text("还需等待 " + remainingTime + " 秒");
            } else {
                $("#generateCode").prop("disabled", false); // 启用按钮
                $("#remainingTimeInfo").text(""); // 清空剩余等待时间
            }
        
            $("#generateCode").text("获取验证码");
        }        
    });

    // 密码确认
    $("#confirmPassword").on('input', function () {
        var password = $("#password").val();
        var confirmPassword = $(this).val();

        if (password !== confirmPassword) {
            $(this).get(0).setCustomValidity('密码不匹配');
        } else {
            $(this).get(0).setCustomValidity('');
        }
    });
});