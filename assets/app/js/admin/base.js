import '../../styles/admin/base.scss';


/**
 * START Région Accordion elements
 * Permet d'initialiser l'accordion de Semantic UI afin que les éléments dans les composants MonsieurBizz puissent se collapser
 */
import Accordion from 'semantic-ui-css'

const initAccordion = () => {
    $('.ui.accordion')
    .accordion()
  
}

let mutAtionObserver = new MutationObserver(initAccordion);
let richContainer = document.querySelectorAll('.uie-panels')
let observerOptions = {
    childList: true,
    attributes: true,
    characterData: false,
    subtree: true,
    attributeFilter: ['one', 'two'],
    attributeOldValue: false,
    characterDataOldValue: false
  };

richContainer.forEach(element => mutAtionObserver.observe(element, observerOptions))

/**
 * END Région Accordion elements
 */