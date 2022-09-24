import '../../styles/admin/base.scss';

/**
 * START Région Accordion elements
 * Permet d'initialiser l'accordion de Semantic UI afin que les éléments dans les composants MonsieurBizz puissent se collapser
 */
 

 
const initComponent = () => {
  initSelect2()
  initAccordion()
}

const initAccordion = () => {
  $('.ui.accordion_alt').accordion();
}

const initSelect2 = () => {
  $(() => {
    $('.select2-image').select2({
      templateResult: formatState,
      templateSelection: formatState
    });
  });

  $(() => {
    $('.select2-standard').select2();
  });
}

let mutAtionObserver = new MutationObserver(initComponent);
let richContainer = document.querySelectorAll('.uie-panels')
let observerOptions = {
  childList: false,
  attributes: true,
  subtree: false,
};

richContainer.forEach(element => mutAtionObserver.observe(element, observerOptions))

/**
 * END Région Accordion elements
 */

/**
 * START Région Select2
 */
$(document).ready(function () {
  $('.select2-image').select2({
    templateResult: formatState,
    templateSelection: formatState

  });
  
});


const formatState = (opt) => {
  if (!opt.id) {
    return opt.text;
  }
  // console.log(opt)
  let optImage = $(
    '<span style="display:flex; align-items: center; padding: 5px 0;">' +
    '<img src="/media/cache/resolve/thumbnail_webp/' + opt.text + '" style="width:60px; max-height: 50px; margin-right: 20px;">' + opt.text +
    '</span>'
  )
  return optImage;
}
/**
 * END Région Select2
 */
 $(".ui.image").on("click", function() {
  let elem = $(this);
  let slug = elem.data( "slug" );
  $(document).find(".ui.image_modal[data-slug='" + slug + "']")
    .modal('setting', 'transition', 'fly left')  
    .modal('show')
  ;
});

