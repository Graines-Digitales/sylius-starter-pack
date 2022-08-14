import { Tooltip, Toast, Popover } from 'bootstrap';
import { tns } from 'tiny-slider';
import toastr  from 'toastr';


import '../styles/base.scss';
import 'toastr/build/toastr.css'
// ------------------------------------
// GESTION DES FORMULAIRES
// ------------------------------------

// Affichage messages flash
toastr.options = {
  closeButton: false,
  debug: false,
  newestOnTop: false,
  progressBar: false,
  positionClass: 'toast-top-center',
  preventDuplicates: false,
  onclick: null,
  showDuration: '300',
  hideDuration: '800',
  timeOut: 2500,
  // "extendedTimeOut": 0
};

$(() => {
 
  let isSubmit = false;
  $(document).on('click', '.btn-form-submit', function(e) {
    e.preventDefault();
    var elem = $(this);
    var elemHtml = elem.html();
    var currentElem = $(this);
    var formId = currentElem.closest("form").attr('id');
    var $form = $("#" + formId);

    elem.addClass('loading');
    elem.html('<span class="spinner-border sr-only"></span>&nbsp;Loading...');

    // console.log($('#files')[0].files);
    // return false;
    var formData = $form.getFormData();
   
    if (isSubmit === false) {
      isSubmit = true;
      if ($form.valid()) {  
        // return false;
        $.ajax({
          url: '/schema/save/form/message',
          method: 'POST',
          contentType: false,
          processData: false,
          data: formData,
          success(data) {
            toastr.success('Votre message à bien été envoyé');
            // $form.get(0).reset();
            elem.removeClass('loading'); 
                        elem.html(elemHtml);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            toastr.info('Une erreur est survenue');
          }
        });
      } else {
        elem.removeClass('loading'); 
        elem.html(elemHtml);
        toastr.info('Une erreur est survenue');
      }
      isSubmit = false;

      return false;
    }
  });

  $.validator.addMethod('filesize', function (value, element, param) {
    
    return this.optional(element) || (element.files[0].size <= param)
  }, 'File size must be less than {0}');


  $.validator.addMethod("valueNotEquals", function(value, element, arg){

    return arg !== value;
  }, "Value must not equal arg.");

  $.fn.getFormData = function () {
    let data = $(this).serializeArray();
    $('form input:checkbox').each(function () {
      data.push({ name: this.name, value: this.checked });
    });
    
    let formData = new FormData();
    formData.append('origin', $(this).attr('id'));
    data.forEach(function(item){
      formData.append(item.name, item.value)
    });
    if($('#file').length > 0) {
      formData.append('file', $('#file')[0].files[0]);
    }
    if($('#files').length > 0) {
      var files = $("#files").get(0).files;
      $.each(files,function(idx,elm){
        formData.append('file'+idx, elm);
      });
    }
    
    return formData;
  };

 
  const $forms = $('.contact-form-default__form');
  var validators = [];
  $.ajax({
    url: '/schema/constraints/form/message',
    method: 'POST',
    success(data) {
      $forms.each(function( form ) {
        let $form =  $( this );
        
        validators[$form.attr('id')] = $form.validate(data);
      });
    }
  });
});

// POPINS FORM
// --------------------------------
const popinButtons = document.querySelectorAll('[data-popin]');

popinButtons.forEach((button) => {
  button.addEventListener('click', (event) => {
    event.preventDefault();


    // selection de la popin en fonction du data-popin du button
    // et ajout de la class open
    const popin = document.getElementById(button.dataset.popin);
    popin.classList.add('open');

    /**
     * 
     */
    var headline = event.currentTarget.dataset.headline;
    if(typeof headline != 'undefined') {
      let h3 = popin.querySelector('h3.headline');
      let input = popin.querySelector('input[name="subject"]')
      input.value = 'Candidature offre : ' + headline;
      h3.innerHTML = 'Candidature offre : ' + headline
    }

    // selection des deux éléments qui permettent de fermer la popin au click
    // et suppression de la class open
    const exits = popin.querySelectorAll('.popin-exit');
    exits.forEach((exit) => {
      exit.addEventListener('click', (event) => {
        event.preventDefault();
        popin.classList.remove('open');
      });
    });
  });
});



