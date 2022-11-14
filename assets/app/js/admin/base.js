import '../../styles/admin/base.scss';

const formatState = (opt) => {
  if (!opt.id) {return opt.text;
  }
  // console.log(opt)
  const optImage = $(
    '<span style="display:flex; align-items: center; padding: 5px 0;">' +
    `<img class="thumbnail_webp" src="/media/cache/thumbnail_webp/${opt.text}" style="width:100px; max-height: 150px; margin-right: 20px;">${opt.text
    }</span>`,
  );
  return optImage;
};

const formatState2 = (opt) => {
  if (!opt.id) {
    return opt.text;
  }
  // console.log(opt)
  const optImage = $(
    '<span style="display:flex; align-items: center; padding: 5px 0;">' +
    `<img src="/media/icon/${opt.text}" style="width:80px; max-height: 150px; margin-right: 20px;">${opt.text
    }</span>`,
  );
  return optImage;
};


$('.ui.image').on('click', function () {
  const elem = $(this);
  const slug = elem.data('slug');
  $(document).find(`.ui.image_modal[data-slug='${slug}']`)
    .modal('setting', 'transition', 'fly left')
    .modal('show');
});


const initComponent = (mutations) => {
  initSelect2();
  initAccordion();
};

const initAccordion = () => {
  const accordions = $('[data-form-collection="list"]');
  accordions.each(function (index) {
    const elem = $(this);

    if (elem.children().length > 0) {
      const attr = elem.attr('data-accordion');
      if (typeof attr === 'undefined') {
        elem.accordion();
        elem.attr('data-accordion', true);
        const childs = elem.find('.content');
        childs.each(function (index) {
          const child = $(this);
          if (!child.hasClass('active')) {
            child.toggle();
          } else {
            elem.find('.title').removeClass('active');
            child.removeClass('active');
            child.toggle();
          }
        });
      }
    }
  });
};

const initSelect2 = () => {
  $('.select2-image').each(function (index) {
    const elem = $(this);
    const attr = elem.attr('data-select2-id');
    // console.log(".select2-image")
    // console.log(attr)
    if (typeof attr === 'undefined' || attr === false) {
      elem.select2({
        templateResult: formatState,
        templateSelection: formatState,
      });
    }
  });
  $('.select2-icon').each(function (index) {
    const elem = $(this);
    const attr = elem.attr('data-select2-id');
    // console.log(".select2-icon")
    // console.log(attr)
    if (typeof attr === 'undefined' || attr === false) {
      elem.select2({
        templateResult: formatState2,
        templateSelection: formatState2,
      });
    }
  });
  $('.select2-standard').each(function (index) {
    const elem = $(this);
    const attr = elem.attr('data-select2-id');
    // console.log(".select2-standard")
    // console.log(attr)
    if (typeof attr === 'undefined' || attr === false) {
      elem.select2();
    }
  });
};


const componentsContainerObserver = new MutationObserver(initComponent);
const componentsContainer = document.querySelectorAll('body');
componentsContainer.forEach((element) => {
  
  componentsContainerObserver.observe(element, {
    childList: true,
    subtree: true
  });

});

jQuery(() => {
  initSelect2();
  // initAccordion();
  const accordions = $('.ui.accordion_alt > div');
  accordions.each(function (index) {
    const elem = $(this);
    const attr = elem.attr('data-accordion');
    if (typeof attr === 'undefined') {
      elem.accordion();
      elem.attr('data-accordion', true);
      const childs = elem.find('.content');
      childs.each(function (index) {
        const child = $(this);
        if (!child.hasClass('active')) {
          child.toggle();
        }
      });
    }
  });
});


// const initAccordion = () => {
//   $('.ui.accordion_alt').accordion();
// }

// const initSelect2 = (

// ) => {
//   $(() => {
//     $('.select2-image').select2({
//       templateResult: formatState,
//       templateSelection: formatState
//     });
//   });

//   $(() => {
//     $('.select2-icon').select2({
//       templateResult: formatState,
//       templateSelection: formatState
//     });
//   });

//   $(() => {
//     $('.select2-standard').select2();
//   });
// }