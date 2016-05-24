$(document).ready(function() {
    
    $('.carousel-inner .item:first-child').addClass('active');
    
    $('.carousel').carousel();
});


$('.product-remove-image').on('click', function(e){
    e.preventDefault();

    var agree = confirm('Are you sure that want delete this image?');
    if(agree) {
        window.location.href = $(this).attr('href');
    } else {
        return false;
    }
});