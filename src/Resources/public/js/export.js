$(function ($) {
   let $exportButton = $('#export-btn');
   let $exportModal = $('#export-modal');

   $exportButton.on('click', function (e) {
      e.stopPropagation();
      e.preventDefault();

      $exportModal.modal('show')
   });

});
