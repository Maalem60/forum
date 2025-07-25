// public/js/script.js

document.addEventListener("DOMContentLoaded", function() {
  tinymce.init({
    selector: 'textarea.tinymce',
    menubar: false,
    plugins: [
      'advlist autolink lists link image charmap preview anchor',
      'searchreplace visualblocks code fullscreen',
      'insertdatetime media table paste code help wordcount'
    ],
    toolbar: 'undo redo | formatselect | bold italic backcolor | ' +
             'alignleft aligncenter alignright alignjustify | ' +
             'bullist numlist outdent indent | removeformat | help',
    height: 300,
    content_css: '//www.tiny.cloud/css/codepen.min.css'
  });
});
$(function() {
  $(".message").each(function() {
      if ($(this).text().length > 0) {
          $(this).slideDown(500).delay(3000).slideUp(500);
      }
  });

  $(".delete-btn").click(function() {
      return confirm("Etes-vous sûr de vouloir supprimer?");
  });

  tinymce.init({
      selector: '.textarea[name="content"]',
      menubar: false,
      plugins: [
          'advlist autolink lists link image charmap print preview anchor',
          'searchreplace visualblocks code fullscreen',
          'insertdatetime media table paste code help wordcount'
      ],
      toolbar: 'undo redo | formatselect | ' +
               'bold italic backcolor | alignleft aligncenter ' +
               'alignright alignjustify | bullist numlist outdent indent | ' +
               'removeformat | help',
      content_css: '//www.tiny.cloud/css/codepen.min.css'
  });
});