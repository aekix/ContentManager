import '../css/content.css';

import $ from 'jquery'

import * as SimpleMDE from 'simplemde';

var simplemde = new SimpleMDE({ element: $("#new_content_text")[0], togglePreview: true , toolbar: ["bold", "italic", "heading", "quote", "strikethrough","code","link","preview"] });


$('.btn').on('click', function () {
    $('#new_content_text').val(simplemde.value());
})

