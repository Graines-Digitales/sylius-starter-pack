import '../../styles/admin/base.scss';

const formatState = (opt) => {
  if (!opt.id) {return opt.text;
  }
  // console.log(opt)
  // const optImage = $(
  //   `<div style="padding: 5px 0;font-size:0.8rem;">
  //   <img class="thumbnail_webp" src="/media/cache/thumbnail_webp/${opt.text}" style="width:90px; height: 90px;">
    // <span class="img-text">
    //   ${opt.text}
    // </span>
  //   </div>`,
  // );

  const optImage = $(
    `
    <div style="display: flex; align-items: center;margin-left: 3px;">
    <img class="thumbnail_webp" src="/media/cache/thumbnail_webp/${opt.text}" style="width:90px; height: 90px;">
    <b class="img-text" style="margin-left: 10px;">
    ${opt.text}
    </b>
    </div>
  `,
  );

  return optImage;
};

const formatState2 = (opt) => {
  if (!opt.id) {
    return opt.text;
  }
  // console.log(opt)
  const optImage = $(
    `
    <div style="display: flex; align-items: center;margin-left: 3px;">
    <img src="/media/icon/${opt.text}" style="width:70px; height: 70px;">
    <b class="img-text" style="margin-left: 10px;">
    ${opt.text}
    </b>
    </div>
    `,
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

// $('.ui.labeled.icon.button.orange').on('click', function (e) {
//   e.preventDefault();
  
//   $('.ui.modal.test').modal('show');
// });
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
  $('.select2-image').each(function () {
    const elem = $(this);
    const attr = elem.attr('data-select2-id')

    if (typeof attr === 'undefined' || attr === false) {
      elem.select2({
        templateResult: formatState,
        templateSelection: formatState,
      });
    }
  });
  $('.select2-icon').each(function () {
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
const componentsContainer = document.querySelector('body');

  
  componentsContainerObserver.observe(componentsContainer, {
    childList: true,
    subtree: true
  })



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