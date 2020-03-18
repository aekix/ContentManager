import '../css/modal.css';

import $ from 'jquery'

// Get the modal
var modal = document.getElementById("myModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById("myImg");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");

$('.imgModal').on('click', function(){
    $(modal).css('display', "block");
    $(modalImg).attr('src', $(this).attr('src'));
    $(captionText).html($(this).attr('alt'));
})

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
$(span).on('click', function(){
    $(modal).css('display',"none");
})
$('.btnModal').on('click', function () {
    $('.imgModal').click()
})

$('#btn-envoyer').on('click', function () {
    var text = $('#textComment').val();
    $('#textComment').val(null);
    refreshComment(text);
})

window.setInterval(refreshComment, 5000);

function refreshComment(text = null) {
    var idContent = $('#idContent').val();
    $.ajax({
        url: '/content/comment',
        method: 'POST',
        data: {idContent: idContent, text: text}
    }).done(function (data) {
        $.each(data, function (idx, obj) {
            if ($('#comments').find('#'+ obj.id).length){

            }else {
                $('#comments').prepend('<li class="media" id='+ obj.id+'> <a href="#" class="pull-left">\n' +
                    '                                        <img src="/assets/img/avatar-comment.jpg" alt="" class="img-circle">\n' +
                    '                                    </a>\n' +
                    '                                    <div class="media-body">\n' +
                    '                                <span class="text-muted pull-right">\n' +
                    '                                    <small class="text-muted">'+ obj.date +'</small>\n' +
                    '                                </span>\n' +
                    '                                        <strong class="text-success">' + obj.firstname + ' ' + obj.lastname + '</strong>\n' +
                    '                                        <p>\n' + obj.text +
                    '                                        </p>\n' +
                    '                                    </div></li>');
            }
        })

    })
}