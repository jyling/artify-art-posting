$(document).ready(function() {
  $("#deletePost").click(function() {
    $.ajax({
      url: "ajax/ajax-remove-post.php",
      type: "post",
      data: { post: getUrlVars().post },
      success: function(data, status) {
        alert("Post Has be removed");
        location.href = "index.php";
      },
      error: function() {
        console.log("reaction failed");
      }
    });
  });
});
