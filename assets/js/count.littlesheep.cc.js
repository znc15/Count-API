        // 使用 fetch 获取数据
        fetch('https://api.count.littlesheep.cc/visit-count')
            .then(response => response.json())
            .then(jsonData => {
                // 将数据填充到页面相应位置
                document.getElementById("websiteName").textContent = jsonData.websiteName;
                document.getElementById("monitoredSiteUrl").textContent = jsonData.monitoredSiteUrl;
                document.getElementById("totalRequests").textContent = jsonData.totalRequests;

                const siteSpecificRequestsTableBody = document.getElementById("siteSpecificRequests");
                jsonData.siteSpecificRequests.forEach(request => {
                    const row = siteSpecificRequestsTableBody.insertRow();
                    row.insertCell(0).textContent = request.ip;
                    row.insertCell(1).textContent = request.count;
                    row.insertCell(2).textContent = request.source_link;
                    row.insertCell(3).textContent = request.location;
                });
            })
            .catch(error => console.error('Error fetching data:', error));