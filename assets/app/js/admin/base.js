import '../../styles/admin/base.scss';

/**
 * START Région Accordion elements
 * Permet d'initialiser l'accordion de Semantic UI afin que les éléments dans les composants MonsieurBizz puissent se collapser
 */

const initComponent = () => {
  initSelect2()
  initAccordion()
  const addCollectionButton = document.querySelector('[data-form-collection="add"]')
  if(null !== addCollectionButton) {
    addCollectionButton.addEventListener('click', function handleClick(event) {
      const collectionList = document.querySelector('[data-form-collection="list"]')
      let mutationObserver = new MutationObserver(initComponent)
      mutationObserver.observe(collectionList, {
        childList: true,
        attributes: true,
        subtree: true
      })
    })
  }
  console.log('initComponent')  
}

const initObserver = () => {
  
  const editButtons = document.querySelectorAll('.js-uie-edit')
  console.log('editButtons')
  console.log(editButtons)
  editButtons.forEach(button => {
    button.addEventListener('click', function handleClick(event) {
      console.log('editButtons click')
      console.log(button)
      let mutationObserver = new MutationObserver(initComponent)
      let richContainer = document.querySelectorAll('.uie-panels.js-uie-panels-edit')
      richContainer.forEach(element => mutationObserver.observe(element, {
        childList: true,
        attributes: true,
        subtree: true
      }))
    })
  })

  const addButtons = document.querySelectorAll('.js-uie-add')
  console.log('addButtons')
  console.log(addButtons)
  addButtons.forEach(button => {
    button.addEventListener('click', function handleClick(event) {
      console.log('addButtons click')
      console.log(button)
      const cards = document.querySelectorAll('.js-uie-panels-selector .link.uie-card')
      cards.forEach(card => {
        card.addEventListener('click', function handleClick(event) {
          let mutationObserver = new MutationObserver(initComponent)
          let richContainer = document.querySelectorAll('.uie-panels__new.js-uie-panels-new')
          richContainer.forEach(element => mutationObserver.observe(element, {
            childList: true,
            attributes: true,
            subtree: true
          }))
        })
      })
    })
  })
}

const initAccordion = () => {
  $('.ui.accordion_alt').accordion()
}

const initSelect2 = () => {
  $(() => {
    $('.select2-image').select2({
      templateResult: formatState,
      templateSelection: formatState
    })
    $('.select2-icon').select2({
      templateResult: formatState2,
      templateSelection: formatState2
    })
    $('.select2-standard').select2();
  })
}

/**
 * END Région Accordion elements
 */

/**
 * START Région Select2
 */
$(document).ready(function () {
  initSelect2()
})

// $(document).ready(function () {
//   $('.select2-icon').select2({
//     templateResult: formatState2,
//     templateSelection: formatState2
//   });
// });

// $(document).ready(function () {
//   $('.select2-standard').select2();
// });


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



let componentsContainerObserver = new MutationObserver(initObserver)
let componentsContainer = document.querySelectorAll('.components-container')
componentsContainer.forEach(element => {
  console.log('componentsContainerObserver')
  console.log(element)
  componentsContainerObserver.observe(element, {
    childList: true,
    attributes: true,
    subtree: true,
  })
})