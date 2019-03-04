$(document).ready(function () {
    $(".pagination").rPage();
});

function toggle(tag,target){
  target = document.getElementById(target);
  bg = document.getElementById('lbl'+tag.id);
    $(target).slideToggle({
      height: 'toggle'
    });
}
function reloadImage(tag,resizable){
  var isValid = /\.[jp][np][g]$/i.test(tag.value);
  if (!isValid) {
    alert('Only jpg and png files allowed!');
    return null;
  }
  if (tag.files && tag.files[0]) {
    var reader = new FileReader();
    reader.onload = (function(theFile) {
        var image = new Image();
        image.src = theFile.target.result;
        image.onload = function() {
            $('.img-target').attr('src',this.src);
            if (resizable) {
              if (this.width >= this.height) {
                $('.img-target').attr('id','image-zoom-2x');
              }
              else if (this.width < this.height) {
                $('.img-target').attr('id','image-zoom');
              }
            }
        };
    });
    reader.readAsDataURL(tag.files[0]);
  }
}

