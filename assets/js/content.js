import '../css/content.css';

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
 
import * as SimpleMDE from 'simplemde';

var simplemde = new SimpleMDE({ element: $("#new_content_text")[0], togglePreview: true , toolbar: ["bold", "italic", "heading", "quote", "strikethrough","code","link","preview"] });


$('.btn').on('click', function () {
    $('#new_content_text').val(simplemde.value());
})

