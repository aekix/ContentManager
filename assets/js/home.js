import '../css/home.css';

import $ from 'jquery'

$(".publishedContent").click(function(){
    document.location="/content/"+$(this).attr("id");
});

$(".pop").click(function () {

   $(".popupUser").toggle();

});

$(".waitingContent").click(function(){
    document.location="/content/review/"+$(this).attr("id");
});

$(document).ready(function() {
    $('#dataTable').DataTable();
});
