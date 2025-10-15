$(document).ready(function(){
    $('select[name="class"]').on('change',function(){
        var classId = $(this).val();
        
        if(classId){
            $.ajax({
                url: '/ajax/class/'+classId+'/get-students',
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    $('select[name="student"]').empty();
                    $('select[name="student"]').append('<option value="">Select Student </option>');
                    console.log(data);
                    $.each(data, function(key, value){
                        $('select[name="student"]').append('<option value="'+key+'">'+ value +'</option>');
                    });
               }
            });
        } else {
            $('select[name="student"]').empty();
        }
    });
});