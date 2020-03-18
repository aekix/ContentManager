import $ from 'jquery'

$(".usersList").click(function(){
    document.location="/user/admin/"+$(this).attr("id");
});

$('.btn-send').on('click', function () {
    $(document.user_update).submit();
})
