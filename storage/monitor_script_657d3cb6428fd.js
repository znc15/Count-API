var urlToken = '657d3cb6428fd';
// 在这里添加你的监测脚本内容

// 通知服务器脚本已经被访问
var xhr = new XMLHttpRequest();
xhr.open("GET", "https://dev.local.count.littlesheep.cc//links/record_visit.php?token=" + urlToken, true);
xhr.send();