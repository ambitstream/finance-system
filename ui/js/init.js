var d = new Date();

var currDate = d.getDate();
var currMonth = d.getMonth();
var currYear = d.getFullYear();

var dateStr = currYear + "-" + currDate + "-" + currMonth;

var includeCats = [];

$(document).ready( function(){

	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd',
		weekStart: 1
	});
	

    $(".swap-table-data").each(function () {
        var $this = $(this);
        var newrows = [];
        $this.find("tr").each(function () {
            var i = 0;
            $(this).find("td,th").each(function () {
                i++;
                if (newrows[i] === undefined) {
                    newrows[i] = $("<tr></tr>");
                }
                newrows[i].append($(this));
            });
        });
        $this.find("tr").remove();
        $.each(newrows, function () {
            $this.append(this);
        });
    });
	
	
	
});


function initFilter() {
	$(".grouped-checkbox:checked").each(function(index) {
		includeCats[index] = $(this).val();		
	});	
	var includeCatsComma = includeCats.join(",");
	$("#include").val( includeCatsComma );
	$("#filter").submit();
	
}
// $('#checkall').toggle(
//    function() { 
// 	   $("#filter input[type=checkbox]").each(function(){
// 		   $(this).attr('Checked','Checked');
// 	   });
//    },
//    function() { 
//        $("#filter input[type=checkbox]").each(function(){
// 		   $(this).removeAttr('Checked'); 
// 	   });
//    }
// );


$('#checkall').on('click', function() {
   var $checks  = $('#filter input[type=checkbox]');
   var $ckall = $(this);

    $.each($checks, function(){
        $(this).prop("checked", $ckall.prop('checked'));
    });
});


// 
// $('#filter input[type=checkbox]').on('click', function(e){
//    $('#checkall').prop('checked', false);
// });