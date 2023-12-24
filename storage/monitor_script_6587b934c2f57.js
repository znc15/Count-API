var urlToken = '6587b934c2f57';
// This is Count API jscript

// Serve is normal
var xhr = new XMLHttpRequest();
xhr.open("GET", "https://dev.local.count.littlesheep.cc/includes/links/record_visit.php?token=" + urlToken, true);
xhr.send();