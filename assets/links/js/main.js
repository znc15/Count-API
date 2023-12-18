$(document).ready(function () {
  // 显示加载中的提示
  $("#loadingSpinner").show();

  // 获取当前页面的page参数
  var currentPage = new URL(window.location.href).searchParams.get("page");

  // 使用 jQuery 的 load 方法加载 display_links.php，并传递page参数
  $("#displayLinksContainer").load(
    "../includes/links/display_links.php?page=" + currentPage,
    function () {
      // 隐藏加载中的提示
      $("#loadingSpinner").hide();
    }
  );
});
