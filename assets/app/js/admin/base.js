import '../../styles/admin/base.scss';

/**
 * START Région Accordion elements
 * Permet d'initialiser l'accordion de Semantic UI afin que les éléments dans les composants MonsieurBizz puissent se collapser
 */

const initComponent = () => {
  console.log('initComponent')  
  initSelect2()
  // initAccordion()
 
  
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
        subtree: false
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

          let richContainer = document.querySelectorAll('.uie-panels__new.js-uie-panels-new')

          let mutationObserver1 = new MutationObserver(initComponent)
          richContainer.forEach(element => mutationObserver1.observe(element, {
            childList: true,
            attributes: true,
            subtree: false
          }))
          let mutationObserver2 = new MutationObserver(initSelect2ForCollection)
          richContainer.forEach(element => mutationObserver2.observe(element, {
            childList: true,
            attributes: true,
            subtree: false
          }))
        
        })
      })

      
      
    })
  })
}

const initAccordion = () => {
  $('.ui.accordion_alt').accordion()
}

const initSelect2ForCollection = () => {

  const addCollectionButton = document.querySelector('#component_cards_cards [data-form-collection="add"]')
  console.log('addCollectionButton')
  console.log(addCollectionButton)
  if(null !== addCollectionButton) {
     // buttons.forEach(button => {
      addCollectionButton.addEventListener('click', function handleClick(event) {
        console.log('click')
        // const addCollectionButton = document.querySelector('[data-form-collection="add"]')
        // console.log('initSelect2ForCollection addCollectionButton')
        // console.log(addCollectionButton)
        const collectionLists = document.querySelectorAll('#component_cards_cards [data-form-collection="item"]')
        console.log('collectionLists')
        console.log(collectionLists)
        var lastElement = collectionLists[collectionLists.length - 1]
        if(typeof lastElement === "undefined") {
          const selector = '#component_cards_cards [data-form-collection-index="0"]'
          // const collection = document.querySelector(selector)
          initSelect2(selector)
          // console.log(collection)
        } else {
          
          const currentIndex = parseInt(lastElement.dataset.formCollectionIndex)
          const newIndex = currentIndex + 1
          const selector = '#component_cards_cards [data-form-collection-index="' + newIndex +'"]'
          // const collection = document.querySelector(selector)
          console.log(lastElement)
          console.log(lastElement.className)
          console.log(lastElement.dataset.formCollectionIndex)
          initSelect2(selector)
          // console.log(collection)
        }
        
        // collectionLists.forEach(collection => {
          // let mutationObserver = new MutationObserver(initComponent)
          // mutationObserver.observe(collection, {
          //   childList: true,
          //   attributes: true,
          //   subtree: false
          // })
        // })
        
       
      // })
    })
  }
 
}

const initSelect2 = (selector = null) => {
  
  if(null !== selector) {
    $(() => {
      console.log('initSelect2 selector')
      $(selector + ' .select2-image').select2({
        templateResult: formatState,
        templateSelection: formatState
      })
      $(selector + ' .select2-icon').select2({
        templateResult: formatState2,
        templateSelection: formatState2
      })
      $(selector + ' .select2-standard').select2()
    })
  } else {
    console.log('initSelect2')
    $(() => {
      $('.select2-image').select2({
        templateResult: formatState,
        templateSelection: formatState
      })
      $('.select2-icon').select2({
        templateResult: formatState2,
        templateSelection: formatState2
      })
      $('.select2-standard').select2()
    })
  }
  
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