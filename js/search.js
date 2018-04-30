$(function () {

//$("#search_id").validate({
//                rules: {
//                    search_id:{
//                        lettersonly: true
//                    }
//                }
//
//            })
            
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
//                    $('#balance').prependTo($('#container_search'));
                }
            })
        } else {
            
            $('#main_container').show();
            $('#container_search').hide();
        }
    })
    
    
            
    

});