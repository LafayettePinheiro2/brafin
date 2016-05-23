$(document).ready(function() {
});


$('#product-remove-image').on('click', function(e){
    e.preventDefault();

    var agree = confirm('Are you sure that want delete this image?');
    if(agree) {
        window.location.href = $(this).attr('href');
    } else {
        return false;
    }
});