// 将流量值转换为相应单位的函数
function convertTrafficUnits(trafficValue) {
  if (trafficValue >= 1e12) {
    return (trafficValue / 1e12).toFixed(2) + " TB";
  } else if (trafficValue >= 1e9) {
    return (trafficValue / 1e9).toFixed(2) + " GB";
  } else {
    return trafficValue.toFixed(2) + " MB";
  }
}

// 使用Fetch API异步获取数据
fetch("../../home/calculate_traffic.php")
  .then((response) => response.json())
  .then((data) => {
    // 处理totalRequestCount的数据
    const totalRequestCountElement =
      document.getElementById("totalRequestCount");
    totalRequestCountElement.innerText = `${data.totalRequestCount[0].total_request_count}`;

    // 处理totalRequestCountToday的数据
    const totalRequestCountTodayElement = document.getElementById(
      "totalRequestCountToday"
    );
    totalRequestCountTodayElement.innerText = `${data.totalRequestCountToday[0].total_request_count_today}`;

    // 处理totalTraffic的数据并进行单位转换
    const totalTrafficElement = document.getElementById("totalTraffic");
    const totalTrafficValue = parseFloat(data.totalTraffic[0].total_traffic);
    totalTrafficElement.innerText = `${convertTrafficUnits(totalTrafficValue)}`;

    // 处理totalTrafficToday的数据并进行单位转换
    const totalTrafficTodayElement =
      document.getElementById("totalTrafficToday");
    const totalTrafficTodayValue = parseFloat(
      data.totalTrafficToday[0].total_traffic_today
    );
    totalTrafficTodayElement.innerText = `${convertTrafficUnits(
      totalTrafficTodayValue
    )}`;

    // 处理topDomains的数据并生成表格
    const topDomainsElement = document.getElementById("topDomains");
    const topDomainsData = data.topDomains;

    let topDomainsTableHTML = '<table class="table table-hover">';
    topDomainsTableHTML += "<thead>";
    topDomainsTableHTML += "<tr>";
    topDomainsTableHTML += '<th class="border-top-0">域名</th>';
    topDomainsTableHTML += '<th class="border-top-0">地区</th>';
    topDomainsTableHTML += '<th class="border-top-0">备注</th>';
    topDomainsTableHTML += '<th class="border-top-0">总请求量</th>';
    topDomainsTableHTML += '<th class="border-top-0">总流量</th>';
    topDomainsTableHTML += "</tr>";
    topDomainsTableHTML += "</thead>";
    topDomainsTableHTML += "<tbody>";

    topDomainsData.forEach((domain) => {
      topDomainsTableHTML += "<tr>";
      topDomainsTableHTML += `<td>${domain.domain}</td>`;
      topDomainsTableHTML += `<td>${domain.country}</td>`;
      topDomainsTableHTML += `<td>${domain.remark}</td>`;
      topDomainsTableHTML += `<td>${domain.total_request_count}</td>`;
      topDomainsTableHTML += `<td>${convertTrafficUnits(
        parseFloat(domain.total_traffic)
      )}</td>`;
      topDomainsTableHTML += "</tr>";
    });

    topDomainsTableHTML += "</tbody>";
    topDomainsTableHTML += "</table>";

    topDomainsElement.innerHTML = topDomainsTableHTML;

    // 处理柱状图
    const barChartCanvas = document.getElementById("barChart").getContext("2d");
    const dates = data.weeklyRequestCount.map((entry) => entry.visit_date);
    const requestCounts = data.weeklyRequestCount.map(
      (entry) => entry.daily_request_count
    );

    new Chart(barChartCanvas, {
      type: "bar",
      data: {
        labels: dates,
        datasets: [
          {
            label: "每日请求量",
            data: requestCounts,
            backgroundColor: "#5b6be8",
            borderColor: "#5b6be8",
            borderWidth: 1,
            hoverBackgroundColor: "#5b6be8",
            hoverBorderColor: "#5b6be8",
          },
        ],
      },
      options: {
        scales: {
          y: {
            beginAtZero: true, // 不从 0 开始
          },
        },
      },
    });
  })
.catch((error) => console.error("获取数据时出错：", error));

// 获取 cookie 值的函数
function getCookie(name) {
  var match = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
  if (match) return match[2];
}

// 在你的代码中使用 getCookie 函数
var token = getCookie("token");

// 检查 token 是否存在
if (!token) {
  // 如果存在，执行相应的操作
  // 例如，跳转到 home/index.php
  window.location.href = "../home/login.php";
}

// 使用异步请求检查 install.lock 文件内容
fetch("../install/install.lock")
  .then((response) => response.text())
  .then((data) => {
    // 判断 install.lock 文件内容是否为 false
    if (data.trim().toLowerCase() === "false") {
      // 跳转到 index.php
      window.location.href = "../install";
    }
  })
  .catch((error) => {
    // 处理错误
    console.error("Error fetching install.lock:", error);
  });
