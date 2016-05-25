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

<<<<<<< HEAD

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
=======
$('.userId').on('click', function(e){
    e.preventDefault();

    var agree = confirm('Are you sure you want to make this user administrator?');
    if(agree) {
        window.location.href = $(this).attr('href');
    } else {
        return false;
    }
});
>>>>>>> 1f56aeb2f0e2755a777f7c623c55ee36e9348182
