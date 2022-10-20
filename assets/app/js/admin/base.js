import '../../styles/admin/base.scss';



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


 $(".ui.image").on("click", function() {
  let elem = $(this)
  let slug = elem.data( "slug" )
  $(document).find(".ui.image_modal[data-slug='" + slug + "']")
    .modal('setting', 'transition', 'fly left')  
    .modal('show')
  ;
});


const initComponent = () => {
  
  initSelect2()
  initAccordion()
}

const initAccordion = () => {
  
  const accordions = $('[data-form-collection="list"]');
  accordions.each(function( index ) {
    let elem = $( this );
   
    if ( elem.children().length > 0 ) {
      let attr = elem.attr('data-accordion');
      if (typeof attr === 'undefined') {
        elem.accordion();
        elem.attr('data-accordion', true);
        let childs = elem.find('.content');
        childs.each(function( index ) {
          let child = $( this );
          if(!child.hasClass('active')) {
            child.toggle();
          } else {
            elem.find('.title').removeClass('active');
            child.removeClass('active');
            child.toggle();
          }
        })
      }
    }
  
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


let componentsContainerObserver = new MutationObserver(initComponent)
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

  const accordions = $('.ui.accordion_alt > div');
  accordions.each(function( index ) {
    const elem = $( this );
    let attr = elem.attr('data-accordion');
    if (typeof attr === 'undefined') {
      elem.accordion();
      elem.attr('data-accordion', true);
      let childs = elem.find('.content');
        childs.each(function( index ) {
          let child = $( this );
          if(!child.hasClass('active')) {
            child.toggle();
        
          } 
          
        })
    }
  });

});
