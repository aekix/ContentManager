import '../css/home.css';

import $ from 'jquery'

import * as SimpleMDE from 'simplemde';

var simplemde = new SimpleMDE({ element: $("#new_content_text")[0], togglePreview: true , toolbar: ["bold", "italic", "heading", "quote", "strikethrough","code","link","preview"] });


$('.btn').on('click', function () {
    $('#new_content_text').val(simplemde.value());
})

$('.approval').on('click', function () {
    var id = $('#idContent').val();
    $.ajax({
        url : '/content/approval',
        method : 'POST',
        data : {id : id}
    }).done(function (response) {
        $('.nb_approval').html(response['yes']);
        $('.nb_refused').html(response['no']);
    })
    if ($('.alreadyReview')) {
        $('.alreadyReview').css('color', 'forestgreen');
        $('.alreadyReview').css('display', 'block');
        $('.alreadyReview').html('Vous avez approuver ce contenu');

    }
})

$('.refuse').on('click', function () {
    var id = $('#idContent').val();
    $.ajax({
        url : '/content/refuse',
        method : 'POST',
        data : {id : id}
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