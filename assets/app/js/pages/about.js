import { tns } from 'tiny-slider';

import '../../styles/pages/_about.scss';

// const sliderHistoryContainer = document.querySelector('.slider-history .slider__slide-wrapper');
// console.log(sliderHistoryContainer);

// const sliderHistory = tns({
//   "container": '.slider-default--history__slide-wrapper',
//   "items": 1,
//   "slideBy": 'page',
//   // controls: false,
//   // controlsPosition: 'bottom',
//   // controlsContainer: '#customize-controls',
//   // nav: false,
//   // navPosition: 'bottom',
//   "autoplay": false,
//   // autoplayButtonOutput: false,
//   // mouseDrag: true,
//   // autoplayTimeout: 5000,
//   // lazyload: true,
//   "autoWidth": true,
//   "edgePadding": 50,
//   "swipeAngle": false,
//   "speed": 3500,
//   "center": true,
//   "rewind": true,
//   // "loop": true,
//   // "autoplayHoverPause": true,
//   "autoplayTimeout": 2500,
// });

var sliderHistoryTwin = tns({
  "container": '.slider-default--history__slide-wrapper-twin',
  "items": 1,
  "slideBy": 'page',
  // "slideBy": 2.6,
  "controls": true,
  // controlsPosition: 'bottom',
  "controlsContainer": '#customize-controls',
  "nav": true,
  // "navPosition": 'bottom center',
  "navContainer": "#customize-thumbnails",
  // "navAsThumbnails": true,
  "autoplay": true,
  "autoplayButtonOutput": false,
  "mouseDrag": true,
  // autoplayTimeout: 5000,
  "lazyload": true,
  "autoWidth": true,
  // "edgePadding": 55, 
  // "gutter": 25, 
  // "swipeAngle": false,
  "speed": 5500,
  "center": true,
  "rewind": true,
  "loop": false,
  "touch": true,
  "autoplayHoverPause": true,
  "autoplayTimeout": 3500
});



// bind function to event
// sliderHistoryTwin.events.on('dragMove', function (info, eventName) {
//   // direct access to info object
//   console.log(info.event.type, info.container.id);
// });