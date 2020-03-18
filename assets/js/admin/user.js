import $ from 'jquery'

$(".usersList").click(function(){
    document.location="/user/admin/"+$(this).attr("id");
});