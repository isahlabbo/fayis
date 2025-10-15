$(document).ready(function(){
    $('select[name="section"]').on('change',function(){
        var sectionId = $(this).val();
        
        if(sectionId){
            $.ajax({
                url: '/ajax/section/'+sectionId+'/get-classes',
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    $('select[name="class"]').empty();
                    $('select[name="class"]').append('<option value="">Select Student Class</option>');
                    $.each(data, function(key, value){
                        $('select[name="class"]').append('<option value="'+key+'">'+ value +'</option>');
                    });
               }
            });
        } else {
            $('select[name="class"]').empty();
        }
    });
});