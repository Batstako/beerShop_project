$(function () {


    $('#search_id').on('keyup', function (e) {
        $('#main_container').hide();
        var text = $(this).val();
        if (text !== '') {
            $.ajax({
                type: 'GET',
                url: 'search.php',
                data: 'txt=' + text,
                success: function (data) {
                    $('#container_search').html(data).show();
                    catalog();

                    // var cartCount = 0,
                    //     buy = $('.btn'),
                    //     span = $('.number'),
                    //     cart = $('.cart'),
                    //     quickview = $('.quickviewContainer'),
                    //     quickViewBtn = $('.quickview'),
                    //     mainDivInfo = $('.product'),
                    //     close = $('.quickviewContainer .close'),
                    //     minicart = [],
                    //     totalPrice = [],
                    //     miniCartPrice;
                    //
                    // buy.on('click', addToCart);
                    // quickViewBtn.on('click', quickView);
                    // cart.on('click', showMiniCart);
                    // close.on('click', function(){
                    //     quickview.removeClass('active');
                    //     mainDivInfo.removeClass('class_for_border')
                    // });
                    //
                    // function quickView() {
                    //     var description = $(this).parent().find('.description').text(),
                    //         header = $(this).parent().find('.header').text(),
                    //         price = $(this).parent().find('.price').text(),
                    //         picture = $(this).parent().find('.beerPicture').attr('src'),
                    //         quickViewHeader = $('.quickviewContainer .headline'),
                    //         quickViewPrice = $('.quickviewContainer .price'),
                    //         quickViewDescription = $('.quickviewContainer .description'),
                    //         quickViewPicture = $('.quickviewContainer .picture');
                    //     clearTimeout(timeQuick);
                    //     if(quickview.hasClass('active') || mainDivInfo.hasClass('class_for_border')){
                    //         quickview.removeClass('active');
                    //         mainDivInfo.removeClass('class_for_border');
                    //         var timeQuick = setTimeout(function(){
                    //             quickview.addClass('active');
                    //             // $(this).parent().addClass('class_for_border');
                    //         }, 300);
                    //     } else{
                    //         quickview.addClass('active');
                    //         $(this).parent().addClass('class_for_border');
                    //     }
                    //     $(this).parent().addClass('class_for_border');
                    //
                    //     quickViewHeader.text(header);
                    //     quickViewHeader.addClass("text_class")
                    //     quickViewPrice.text("Price: " + price);
                    //     quickViewDescription.text(description);
                    //     quickViewDescription.prepend('<p class="my-3 text_class">Details: </p>')
                    //     quickViewPicture.attr('src', picture);
                    // }
                    //
                    // function showMiniCart() {
                    //     $('.mini').toggleClass('visible');
                    // }
                    //
                    // function addToCart() {
                    //     var self = $(this),
                    //         productName = $(this).parent().find('.header').text(),
                    //         miniCartNames = $('.products'),
                    //         names = $('.names'),
                    //         price = $(this).parent().find('.price').text(),
                    //         priceInt = parseInt(price);
                    //
                    //     totalPrice.push(priceInt);
                    //     miniCartPrice = totalPrice.reduce(function(a,b){  return a+b });
                    //     $('.miniprice').text('Total amount: ' + miniCartPrice + ",-");
                    //     minicart.push(productName);
                    //     lastProduct = minicart[minicart.length - 1];
                    //     miniCartNames.text('Your cart lines: ');
                    //     names.append('<p>' + lastProduct + '</p>');
                    //
                    //     cartCount++;
                    //     span.text(cartCount);
                    //     clearTimeout(time);
                    //     if(span.hasClass('update')){
                    //         span.removeClass('update');
                    //         span.addClass('updateQuantity');
                    //         var time = setTimeout(function(){
                    //             span.removeClass('updateQuantity');
                    //             span.addClass('update');
                    //         }, 700);
                    //     } else{
                    //         span.addClass('update');
                    //     }
                    //     if (cartCount == 1){
                    //         cart.toggleClass('icon-basket icon-basket-loaded');
                    //     }
                    //
                    //     $(this).addClass('ok');
                    //     var timeOk = setTimeout(function(){
                    //         self.removeClass('ok');
                    //     }, 1000);
                    // }
                }

            })
        } else {

            $('#main_container').show();
            $('#container_search').hide();
        }
    })

});