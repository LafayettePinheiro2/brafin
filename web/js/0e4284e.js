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

$('.userId').on('click', function(e){
    e.preventDefault();

    var agree = confirm('Are you sure you want to make this user administrator?');
    if(agree) {
        window.location.href = $(this).attr('href');
    } else {
        return false;
    }
});

$('.category-list').on('click', function(e){
    e.preventDefault();

    var clickedLink = $(this);
    var categoryId = {'categoryId' : clickedLink.attr('data-value')};

    $.ajax({
        type: "POST",
        url: clickedLink.attr('href'),
        data: categoryId,
        async: false,
        success: function(response, dataType)
        {
            $('.homepage-products-content').html(response.content);
        },

        error: function(XMLHttpRequest, textStatus, errorThrown)
        {
            $('.homepage-products-content').html('Error, please try again.');
        }
    });
});

$('.conversation-list').on('click', function(e){
    e.preventDefault();

    var conversationId = {'conversationId' : $(this).attr('data-value')};

    $.ajax({
        type: "GET",
        url: $(this).attr('href'),
        data: conversationId,
        async: false,
        success: function(response, dataType)
        {
          $('.message-content').html(response.content);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown)
        {
            $('.message-content').html('Error, please try again.');
        }
    });
});

$('#search-sidebar').on('change paste keyup', function(){
    if($(this).val().length >= 3 || $(this).val().length === 0){
        var search = {'search' : $(this).val()};
        $.ajax({
            url: $(this).attr('data-href'),
            data: search,
            success: function(response, dataType)
            {
                $('.homepage-products-content').html(response.content);
            },

            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                $('.homepage-products-content').html('Error, please try again.');
            }
        });
    }
});
