$(document).ready(function(){
    $('select[name="section"]').on('change',function(){
        var sectionId = $(this).val();
        if(sectionId){
            $.ajax({
                url: '/ajax/section/'+sectionId+'/get-pins',
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    $('select[name="pin"]').empty();
                    $('select[name="pin"]').append('<option value="">Select Pin</option>');
                    $.each(data, function(key, value){
                        $('select[name="pin"]').append('<option value="'+key+'">'+ value +'</option>');
                    });
               }
            });
        } else {
            $('select[name="pin"]').empty();
        }
    });
});