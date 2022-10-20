import '../../styles/admin/base.scss';


const initComponent = () => {
  initSelect2()
  initAccordion()
}

const initAccordion = () => {
  // $('.ui.accordion_alt').accordion()
  $('.ui.accordion_alt').each(function( index ) {
    const elem = $( this );
    const attr = elem.attr('data-form-collection');
    console.log(attr)
    // if (typeof attr === 'undefined' || attr === false) {
    //   elem.select2({
    //     templateResult: formatState,
    //     templateSelection: formatState
    //   });
    // }
  });
}

const initSelect2 = () => {
  $('.select2-image').each(function( index ) {
    const elem = $( this );
    const attr = elem.attr('data-select2-id');
    if (typeof attr === 'undefined' || attr === false) {
      elem.select2({
        templateResult: formatState,
        templateSelection: formatState
      });
    }
  });
  $('.select2-icon').each(function( index ) {
    const elem = $( this );
    const attr = elem.attr('data-select2-id');
    if (typeof attr === 'undefined' || attr === false) {
      elem.select2({
        templateResult: formatState2,
        templateSelection: formatState2
      });
    }
  });
  $('.select2-standard').each(function( index ) {
    const elem = $( this );
    const attr = elem.attr('data-select2-id');
    if (typeof attr === 'undefined' || attr === false) {
      elem.select2();
    }
  });
}


const formatState = (opt) => {
  if (!opt.id) {
    return opt.text;
  }
  // console.log(opt)
  let optImage = $(
    '<span style="display:flex; align-items: center; padding: 5px 0;">' +
    '<img class="mini_webp" src="/media/cache/thumbnail_webp/' + opt.text + '" style="width:60px; max-height: 50px; margin-right: 20px;">' + opt.text +
    '</span>'
  )
  return optImage;
}

const formatState2 = (opt) => {
  if (!opt.id) {
    return opt.text;
  }
  // console.log(opt)
  let optImage = $(
    '<span style="display:flex; align-items: center; padding: 5px 0;">' +
    '<img src="/media/icon/' + opt.text + '" style="width:60px; max-height: 50px; margin-right: 20px;">' + opt.text +
    '</span>'
  )
  return optImage;
}

/**
 * END Région Select2
 */
 $(".ui.image").on("click", function() {
  let elem = $(this)
  let slug = elem.data( "slug" )
  $(document).find(".ui.image_modal[data-slug='" + slug + "']")
    .modal('setting', 'transition', 'fly left')  
    .modal('show')
  ;
});

let componentsContainerObserver = new MutationObserver(initSelect2)
let componentsContainer = document.querySelectorAll('body')
componentsContainer.forEach(element => {
  console.log('componentsContainerObserver')
  console.log(element)
  componentsContainerObserver.observe(element, {
    childList: true,
    attributes: false,
    subtree: true,
  })
})

jQuery(function() {
  initSelect2()
});
