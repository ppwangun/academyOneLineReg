/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {
    
$body = $("body");    
$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
     ajaxStop: function() { $body.removeClass("loading"); }    
});
$(document).on("submit", function(){
    $body.addClass("loading");
});


$('#newacadyr').on("click", function(event){
       // event.preventDefault();
        var $this = $(this);
        $.ajax({
            dataType: 'html',
            url : "newacadyr",
            //data : $this.serialize(),
            success: function( data){
               
                    $('#page-wrapper').html(data)

              ;
              
            }

        }) ;       
    }); 
    
      $("#acad_start_date" ).datepicker({ dateFormat: 'dd-mm-yy',
     changeMonth: true,
      changeYear: true});
      $("#acad_end_date" ).datepicker({ dateFormat: 'dd-mm-yy',
     changeMonth: true,
      changeYear: true});
});
