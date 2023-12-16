<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>修改用户信息</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>修改用户信息</h2>
        <form id="updateForm">
            <div class="form-group">
                <label for="newUsername">新用户名:</label>
                <input type="text" class="form-control" id="newUsername" name="newUsername" required>
            </div>
            <div class="form-group">
                <label for="newEmail">新邮箱:</label>
                <input type="email" class="form-control" id="newEmail" name="newEmail" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="updateUserInfo()">提交</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<!-- Bootstrap Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">提示</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- 这里显示更新成功或失败的消息 -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="refreshPage()">关闭</button>
            </div>
        </div>
    </div>
</div>

<script>
    function updateUserInfo() {
        // 获取用户输入的新用户名和新邮箱
        var newUsername = document.getElementById('newUsername').value;
        var newEmail = document.getElementById('newEmail').value;

        // 创建一个FormData对象，用于将数据发送到服务器
        var formData = new FormData();
        formData.append('newUsername', newUsername);
        formData.append('newEmail', newEmail);

        // 发送POST请求到update_info.php
        fetch('update_info.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            // 使用Bootstrap模态框显示成功或失败消息
            var modalContent = document.getElementById('modalContent');
            modalContent.innerHTML = data;
            $('#myModal').modal('show');
        })
        .catch(error => console.error('Error:', error));
    }

    function refreshPage() {
        location.reload(true); // 刷新页面
    }
</script>

</body>

</html>
