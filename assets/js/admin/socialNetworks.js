import $ from 'jquery'

$(".socialNetworksList").click(function(){
    document.location="/socialNetworks/admin/"+$(this).attr("id");
});
