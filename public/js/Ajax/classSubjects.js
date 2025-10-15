$(document).ready(function(){
    $('select[name="class"]').on('change',function(){
        var classId = $(this).val();
        if(classId){
            $.ajax({
                url: '/ajax/section/class/'+classId+'/get-subjects',
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    $('select[name="subject"]').empty();
                    $('select[name="subject"]').append('<option value="">Select Student Subject</option>');
                    $.each(data, function(key, value){
                        $('select[name="subject"]').append('<option value="'+key+'">'+ value +'</option>');
                    });
               }
            });
        } else {
            $('select[name="subject"]').empty();
        }
    });
});