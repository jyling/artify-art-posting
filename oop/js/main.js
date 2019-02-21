$(document).ready(function () {
    $(".pagination").rPage();
});
function reloadImage(tag){
  if (tag.files && tag.files[0]) {
    var reader = new FileReader();
    reader.onload = (function(theFile) {
        var image = new Image();
        image.src = theFile.target.result;
        image.onload = function() {
            $('.post-img-content').attr('src',this.src);
            if (this.width >= this.height) {
              $('.post-img-content').attr('id','image-zoom-2x');
            }
            else if (this.width < this.height) {
              $('.post-img-content').attr('id','image-zoom');
            }
        };
    });
    reader.readAsDataURL(tag.files[0]);
  }
}
