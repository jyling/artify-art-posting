$(document).ready(function() {
  $("#buy").click(function() {
    $.ajax({
      url: "ajax/ajax-buy.php",
      type: "post",
      data: { post: getUrlVars().post },
      success: function(data, status) {
        console.log(data);
        var output = JSON.parse(data);
        alert(output.msg);
        if (output.success) {
          location.href = "ImageViewer.php?post=" + getUrlVars().post;
        }
      },
      error: function() {
        console.log("buy failed");
      }
    });
  });
});
