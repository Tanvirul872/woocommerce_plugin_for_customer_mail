jQuery(document).ready(function($){

    // AJAX url
    var ajax_url = plugin_ajax_object.ajax_url;

    // Fetch All records (AJAX request without parameter)
    var data = {
        'action': 'employeeList'
    };

    $.ajax({
        url: ajax_url,
        type: 'post',
        data: data,
        dataType: 'json',
        success: function(response){
            // Add new rows to table
            createTableRows(response);
        }
    });






    $('#enquiry').submit(function (event) {
        event.preventDefault();


        alert('hello') ;

        var ajax_url = plugin_ajax_object.ajax_url;

        var form = $('#enquiry').serialize();
        // var form = $("#enquiry").find("input[name!='g-recaptcha-response'],textarea[name='Enquiry']").serialize();


        console.log(form) ;
        // var formdata = new FormData;


        //
        // formdata.append('action', 'enquiry');
        // formdata.append('enquiry', form);
        //
        // $('#imgenquiry').css('display', 'inline-block');
        // $('#submit').css('display', 'none');

        var data = {
            'action': 'contactMailList',
            'formData': form

        };

        $.ajax({
            url: ajax_url,
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(response){
                // Add new rows to table
                alert('success') ;
            }
        });


        // $.ajax(endpoint, {
        //     type: 'POST',
        //     url: ajax_url,
        //     data: formdata,
        //     processData: false,
        //     contentType: false,
        //     success: function (res) {
        //
        //
        //         $("#myModal").modal('show');
        //
        //
        //         $('#imgenquiry').css('display', 'none');
        //         $('#submit').css('display', 'inline-block');
        //         $('#enquiry').fadeOut(200);
        //         // $('#success_message').text('Thanks for your enquiry').show();
        //         $('#enquiry').trigger('reset');
        //         $('#enquiry').fadeIn(500);
        //
        //     },
        //
        //     error: function (err) {
        //
        //     }
        // })

    })




    // Search record
    $('#search').keyup(function(){
        var searchText = $(this).val();

        // Fetch filtered records (AJAX with parameter)
        var data = {
            'action': 'searchEmployeeList',
            'searchText': searchText
        };

        $.ajax({
            url: ajax_url,
            type: 'post',
            data: data,
            dataType: 'json',
            success: function(response){
                // Add new rows to table
                createTableRows(response);
            }
        });
    });

    // Add table rows by reading response object
    function createTableRows(response){
        $('#empTable tbody').empty();

        var len = response.length;
        var sno = 0;
        for(var i=0; i<len; i++){
            var id = response[i].id;
            var emp_name = response[i].emp_name;
            var email = response[i].email;
            var salary = response[i].salary;
            var gender = response[i].gender;
            var city = response[i].city;

            // Add <tr>
            var tr = "<tr>";
            tr += "<td>"+ (++sno) +"</td>";
            tr += "<td>"+ emp_name +"</td>";
            tr += "<td>"+ email +"</td>";
            tr += "<td>"+ salary +"</td>";
            tr += "<td>"+ gender +"</td>";
            tr += "<td>"+ city +"</td>";
            tr += "<tr>";

            $("#empTable tbody").append(tr);
        }
    }
});