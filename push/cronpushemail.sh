#!/bin/bash

# 引入配置文件
source config.sh

# 验证定时任务 token
if [ "$1" != "$cronToken" ]; then
    echo "非法访问"
    exit 1
fi

# 创建数据库连接
connection=$(mysql -h $host -u $db_username -p$db_password $database -P $port)

# 检查连接是否成功
if [ "$connection" == "" ]; then
    echo "连接数据库失败"
    exit 1
fi

# 获取所有同意接收邮件的用户
queryUsers="SELECT * FROM users WHERE receive_email = 1"
resultUsers=$(echo "$queryUsers" | mysql -u $db_username -p$db_password -h $host -P $port $database)

while read -r rowUser; do
    userId=$(echo $rowUser | cut -f1)
    email=$(echo $rowUser | cut -f4)

    # 查询该用户一周内的访问统计数据
    query="SELECT SUM(vc.request_count) as total_requests
              FROM urls url
              JOIN visitcount vc ON url.urltoken = vc.urltoken
              WHERE url.owner = $userId
                AND vc.visit_time >= DATE_SUB(NOW(), INTERVAL 1 WEEK)"
    result=$(echo "$query" | mysql -u $db_username -p$db_password -h $host -P $port $database)

    # 获取总请求量
    totalRequests=$(echo "$result" | tail -n 1)

    # 使用邮件发送命令，这里需要根据你的系统和邮件服务调整
    echo "亲爱的用户，您本周所有网站总共被请求多少次：${totalRequests}" | mail -s "Weekly Visit Count Summary" $email

    echo '推送成功!'
done <<< "$resultUsers"

# 关闭数据库连接
mysqladmin -h $host -u $db_username -p$db_password -P $port shutdown

exit 0
