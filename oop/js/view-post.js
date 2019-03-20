var pages = 1;
var runable = true;
$(document).ready(function() {
  $(".pagination").rPage();

  $("#post-comment-submit").click(function() {
    $(".error").html("");
    var value = $("#comment-field").val();
    $("#comment-field").val("");

    // alert(value);
    value = jQuery.trim(value);
    if (value == "") {
      alert("Please provide some text in the comment field " + value);
    } else {
      $.ajax({
        url: "ajax/ajax-insert-comment.php",
        type: "post",
        data: { input: value, post: getUrlVars().post },
        success: function(data, status) {
          console.log(data);
          output = JSON.parse(data);
          document.getElementById("comment").innerHTML = "";
          if (output.error != undefined) {
            $(".error").html(output.error);
          }
          $("#load-comment").show();
          pages = 0;
          runable = true;
          $("#load-comment").click();
          tempDisable(document.getElementById("post-comment-submit"));
          tempDisable(document.getElementById("comment-field"));
        },
        error: function() {
          console.log("reaction failed");
        }
      });
    }
  });

  $("#load-comment").on("click", function() {
    pages++;
    var id = window.location.search.substr(6);
    var value = $("#comment-field").val();
    $.ajax({
      url: "ajax/ajax-comment.php",
      type: "post",
      data: { page: pages, post: getUrlVars().post },
      success: function(data, status) {
        var output = JSON.parse(data);
        console.log(output);
        if (output.pageleft >= 0) {
          document.getElementById("comment").innerHTML =
            document.getElementById("comment").innerHTML + output.tag;
          $("#load-comment").html("Load more ( " + output.pageleft + " )");
        }
        if (output.pageleft == 0) {
          $("#load-comment").hide();
          runable = false;
        }
      },
      error: function() {
        console.log("follow failed");
      }
    });
  });
});

function countdown(value) {}

function tempDisable(button) {
  var oldValue = button.innerText;

  button.setAttribute("disabled", true);
  var seconds = 5;
  seconds++;
  var x = setInterval(function() {
    --seconds;
    console.log(seconds);
    button.innerHTML = "...Please Wait (" + seconds + "s)...";
    if (seconds == 0) {
      button.innerHTML = "...Please Wait (" + seconds + "s)...";
      clearInterval(x);
    }
  }, 1000);
  setTimeout(function() {
    button.innerHTML = oldValue;
    button.removeAttribute("disabled");
  }, 7000);
}

function like(post, value) {
  $.ajax({
    url: "ajax/ajax-reaction.php",
    type: "post",
    data: { id: post, choice: value },
    success: function(data, status) {
      console.log(data);
      var like_data = JSON.parse(data);
      document.getElementById("like").innerHTML =
        "Like (" + like_data.success.list.like + ")";
      document.getElementById("unlike").innerHTML =
        "Unlike (" + like_data.success.list.dislike + ")";
    },
    error: function() {
      console.log("reaction failed");
    }
  });
}

function follow(e) {
  $.ajax({
    url: "ajax/ajax-follow.php",
    type: "post",
    data: { id: e },
    success: function(data, status) {
      console.log(data);
      var output = JSON.parse(data);
      // console.log(output);
      if (output.success.removed) {
        document.getElementById("follow").innerHTML =
          "Follow (" + output.success.count + ")";
        alert("You have unfollowed " + output.success.target);
      } else {
        document.getElementById("follow").innerHTML =
          "Unfollow (" + output.success.count + ")";
        alert("You have followed " + output.success.target);
      }
    },
    error: function() {
      console.log("follow failed");
    }
  });
}

function getUrlVars() {
  var vars = {};
  var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(
    m,
    key,
    value
  ) {
    vars[key] = value;
  });
  return vars;
}
