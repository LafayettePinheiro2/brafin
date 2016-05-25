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


 $('.category-list').on('click', function(e){
    e.preventDefault();
    
    var categoryId = {'categoryId' : $(this).attr('data-value')};
    
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: categoryId,
        success: function(response, dataType)
        {
            $('.homepage-products-content').html(response.content);
        },

        error: function(XMLHttpRequest, textStatus, errorThrown)
        {
            $('.homepage-products-content').html('Error, please try again.');
//            alert('Error : ' + errorThrown);
        }
    });
});