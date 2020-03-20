import '../css/review.css';

import $ from "jquery";

$('.approval').on('click', function () {
    var id = $('#idContent').val();
    $.ajax({
        url: '/content/approval',
        method: 'POST',
        data: {id: id}
    }).done(function (response) {
        $('.nb_approval').html(response['yes']);
        $('.nb_refused').html(response['no']);
    })
    if ($('.alreadyReview')) {
        $('.alreadyReview').css('color', 'forestgreen');
        $('.alreadyReview').css('display', 'block');
        $('.alreadyReview').html('Vous avez approuvé ce contenu');

    }
})

$('.refuse').on('click', function () {
    var id = $('#idContent').val();
    $.ajax({
        url: '/content/refuse',
        method: 'POST',
        data: {id: id}
    }).done(function (response) {
        $('.nb_approval').html(response['yes']);
        $('.nb_refused').html(response['no']);
    })

    if ($('.alreadyReview')) {
        $('.alreadyReview').css('color', 'red');
        $('.alreadyReview').css('display', 'block');
        $('.alreadyReview').html('Vous avez décliné ce contenu');
    }
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