        document.addEventListener('DOMContentLoaded', () => {
            // 请求后端记录访问次数，并传递用户IP地址
            fetch('https://api.count.littlesheep.cc/record?token=a8fdd75b7678bd336c317d290739aac5ba9b32da02589b7da4ebae0abc63730c', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({}),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`请求失败: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('请求成功:', data);
                })
                .catch(error => {
                    console.error('请求失败:', error);
                });
        });
