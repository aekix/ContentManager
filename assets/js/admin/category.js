import $ from 'jquery'

$(".categoriesList").click(function(){
    document.location="/category/admin/"+$(this).attr("id");
});