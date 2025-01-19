(function($) { "use strict";


// mobile-drop-down
jQuery('.dropdown-icon').on('click',function(){
  // alert()
  // $(this).next('.mob-submenu').slideToggle();
  jQuery(this).toggleClass('active').next('ul').slideToggle();
  jQuery(this).parent().siblings().children('ul').slideUp();
  jQuery(this).parent().siblings().children('.active').removeClass('active');
});

// sticky header

window.addEventListener('scroll',function(){
  const header = document.querySelector('header.style-1, header.style-2, header.style-3, header.style-4, header.style-5');
  header.classList.toggle("sticky",window.scrollY > 0);
});

$('.sidebar-button').on("click", function(){
  $('.main-menu').addClass('show-menu');
});

$('.menu-close-btn').on("click", function(){
  $('.main-menu').removeClass('show-menu');
});

  // Odometer Counter

  $(".counter-single").each(function () {
    $(this).isInViewport(function (status) {
      if (status === "entered") {
        for (
          var i = 0;
          i < document.querySelectorAll(".odometer").length;
          i++
        ) {
          var el = document.querySelectorAll(".odometer")[i];
          el.innerHTML = el.getAttribute("data-odometer-final");
        }
      }
    });
  });
//Detect Closest Edge
function closestEdge(x,y,w,h) {
  var topEdgeDist = distMetric(x,y,w/2,0);
  var bottomEdgeDist = distMetric(x,y,w/2,h);
  var leftEdgeDist = distMetric(x,y,0,h/2);
  var rightEdgeDist = distMetric(x,y,w,h/2);
  var min = Math.min(topEdgeDist,bottomEdgeDist,leftEdgeDist,rightEdgeDist);
  switch (min) {
      case leftEdgeDist:
          return "left";
      case rightEdgeDist:
          return "right";
      case topEdgeDist:
          return "top";
      case bottomEdgeDist:
          return "bottom";
  }
}

//Distance Formula
function distMetric(x,y,x2,y2) {
  var xDiff = x - x2;
  var yDiff = y - y2;
  return (xDiff * xDiff) + (yDiff * yDiff);
}


var boxes = document.querySelectorAll(".direction-hover");

for(var i = 0; i < boxes.length; i++){

  boxes[i].onmouseenter = function(e){
      var x = e.pageX - this.offsetLeft;
      var y = e.pageY - this.offsetTop;
      var edge = closestEdge(x,y,this.clientWidth, this.clientHeight);
      var overlay = this.childNodes[1];
      var image = this.childNodes[0];

      switch(edge){
          case "left":
              //tween overlay from the left
              overlay.style.top = "0%";
              overlay.style.left = "-100%";
              TweenMax.to(overlay, .5, {left: '0%'});
              TweenMax.to(image, .5, {scale: 1.2});
              break;
          case "right":
              overlay.style.top = "0%";
              overlay.style.left = "100%";
              //tween overlay from the right
              TweenMax.to(overlay, .5, {left: '0%'});
              TweenMax.to(image, .5, {scale: 1.2});
              break;
          case "top":
              overlay.style.top = "-100%";
              overlay.style.left = "0%";
              //tween overlay from the right
              TweenMax.to(overlay, .5, {top: '0%'});
              TweenMax.to(image, .5, {scale: 1.2});
              break;
          case "bottom":
              overlay.style.top = "100%";
              overlay.style.left = "0%";
              //tween overlay from the right
              TweenMax.to(overlay, .5, {top: '0%'});
              TweenMax.to(image, .5, {scale: 1.2});
              break;
      }
  };

  boxes[i].onmouseleave = function(e){
      var x = e.pageX - this.offsetLeft;
      var y = e.pageY - this.offsetTop;
      var edge = closestEdge(x,y,this.clientWidth, this.clientHeight);
      var overlay = this.childNodes[1];
      var image = this.childNodes[0];

      switch(edge){
          case "left":
              TweenMax.to(overlay, .5, {left: '-100%'});
              TweenMax.to(image, .5, {scale: 1.0});
              break;
          case "right":
              TweenMax.to(overlay, .5, {left: '100%'});
              TweenMax.to(image, .5, {scale: 1.0});
              break;
          case "top":
              TweenMax.to(overlay, .5, {top: '-100%'});
              TweenMax.to(image, .5, {scale: 1.0});
              break;
          case "bottom":
              TweenMax.to(overlay, .5, {top: '100%'});
              TweenMax.to(image, .5, {scale: 1.0});
              break;
      }
  };
}

// preloader
function counter_num(){
  var count = setInterval(function(){
      var c = parseInt($('.counter').text());
      $('.counter').text((++c).toString());
      if(c == 100){
          clearInterval(count);
          $('.counter').addClass('hide');
          $('.preloader').addClass('active');
      }
  })
};
counter_num();


/* ---------------------------------------------
     Parallax
--------------------------------------------- */



  
$('.grid').isotope({
  itemSelector: '.grid-item',
  });

  
  // filter items on button click
  $('.filter-button-group').on( 'click', 'li', function() {
  var filterValue = $(this).attr('data-filter');
  $('.grid').isotope({ filter: filterValue });
  $('.filter-button-group li').removeClass('active');
  $(this).addClass('active');
  });
  $('.filter-button-group').on( 'click', 'li', function() {
  var filterValue = $(this).attr('data-filter');
  $('.grid-two').isotope({ filter: filterValue });
  $('.filter-button-group li').removeClass('active');
  $(this).addClass('active');
  });

  $('.grid').masonry({
  // options
  itemSelector: '.grid-item',
  });
  $('.grid-two').masonry({
    // options
    itemSelector: '.grid-item-two',
  });


/* ---------------------------------------------
     NiceSelect
--------------------------------------------- */
 
 $('select').niceSelect();
 


/* ---------------------------------------------
     Magnific Popup video
--------------------------------------------- */

$('.popup-youtube').magnificPopup({
  type: 'iframe'
});

// fancybox
// $("a.portfolio-img").fancybox();

$('[data-fancybox="gallery"]').fancybox({
  buttons: [
    "slideShow",
    "thumbs",
    "zoom",
    "fullScreen",
    "share",
    "close"
  ],
  loop: false,
  protect: true
});
// calender
$(function() {
  $( "#datepicker" ).datepicker();
  $( "#datepicker2" ).datepicker();
});



    //===== Nice number js

    
    if ($('input[type="number').length) {
      $('input[type="number"]').niceNumber({
        buttonDecrement:'<i class="bi bi-dash"></i>',
        buttonIncrement:'<i class="bi bi-plus"></i>',
        
      });
  }






// Home 1 Banner
var swiper = new Swiper(".banner1-slider", {
  slidesPerView: 1,
  speed: 1200,
  // spaceBetween: 15,
  effect: 'fade',
  fadeEffect: {
    crossFade: true
  },
  autoplay: true,
  loop: true,
  navigation: {
    nextEl: ".next-btn-1",
    prevEl: ".prev-btn-1",
  },
  pagination: false,
});
// Recent Post slider
var swiper = new Swiper(".recent-post-slider", {
  slidesPerView: 1,
  speed: 1200,
  spaceBetween: 22,
  fadeEffect: {
    crossFade: true
  },
  autoplay: true,
  loop: true,
  navigation: {
    nextEl: ".next-btn",
    prevEl: ".prev-btn",
  },
  pagination: false,
});
// Home 2 Banner
var swiper = new Swiper(".banner2-slider", {
  slidesPerView: 1,
  speed: 1200,
  // spaceBetween: 15,
  effect: 'fade',
  fadeEffect: {
    crossFade: true
  },
  autoplay: true,
  loop: true,
  pagination: {
    el: ".swiper-pagination-g",
  },
});

// New Item List Slider
var swiper = new Swiper(".new-item-sm-slider", {
  loop: true,
  spaceBetween: 22,
  slidesPerView: 6,
  freeMode: true,
  // centeredSlides: true,
  watchSlidesProgress: true,
  navigation: {
    nextEl: ".next-btn-2",
    prevEl: ".prev-btn-2",
  },

  breakpoints: {
    280:{
      slidesPerView: 3,
      spaceBetween: 15
    },
    480:{
      slidesPerView: 4
    },
    768:{
      slidesPerView: 5
    },
    992:{
      slidesPerView: 6
    },
    1200:{
      slidesPerView: 6
    },
    1400:{
      slidesPerView:6
    },
    1600:{
      slidesPerView: 6
    },
  }

});
var swiper2 = new Swiper(".new-item-big-slider", {
  loop: true,
  spaceBetween: 10,
  effect: 'fade',
  fadeEffect: {
    crossFade: true
  },
  thumbs: {
    swiper: swiper,
  },
});


// Popular Items slider
var swiper = new Swiper(".popular-item-slider", {
  slidesPerView: 3,
  spaceBetween: 30,
  // centeredSlides: true,
  loop: true,
  speed:1500,
  autoplay: {
    delay: 2000,
  },
  pagination: {
    el: ".swiper-pagination-xyz",
    clickable: true,
  },
  breakpoints: {
    280:{
      slidesPerView: 1,
      spaceBetween: 15
    },
    480:{
      slidesPerView: 1
    },
    768:{
      slidesPerView: 2
    },
    992:{
      slidesPerView: 3
    },
    1200:{
      slidesPerView: 3
    },
    1400:{
      slidesPerView:3
    },
    1600:{
      slidesPerView: 3
    },
  }
});

// Testimonisl  Slider
var swiper3 = new Swiper(".testimonial-img-slider", {
  loop: true,
  spaceBetween: 22,
  slidesPerView: 3,
  freeMode: true,
  watchSlidesProgress: true,
  direction: 'vertical',
  speed:1500,
  autoplay: {
    delay: 2000,
  },
  navigation: {
    nextEl: ".next-btn-2",
    prevEl: ".prev-btn-2",
  },

  breakpoints: {
    280:{
      slidesPerView: 3,
      spaceBetween: 15,
      direction: 'horizontal',
    },
    480:{
      slidesPerView: 3,
      direction: 'horizontal',
    },
    768:{
      slidesPerView: 3
    },
    992:{
      slidesPerView: 3
    },
    1200:{
      slidesPerView: 3
    },
    1400:{
      slidesPerView:3
    },
    1600:{
      slidesPerView: 3
    },
  }

});
var swiper4 = new Swiper(".testimonial-content-slider", {
  loop: true,
  spaceBetween: 30,
  effect: 'fade',
  speed:1500,
  autoplay: {
    delay: 2000,
  },
  fadeEffect: {
    crossFade: true
  },
  thumbs: {
    swiper: swiper3,
  },
});

// Expart Slider
var swiper = new Swiper(".expart-slider", {
  slidesPerView: 3,
  spaceBetween: 37,
  // centeredSlides: true,
  loop: true,
  speed:1500,
  autoplay: {
    delay: 1500,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    280:{
      slidesPerView: 1,
      spaceBetween: 15
    },
    480:{
      slidesPerView: 2,
      spaceBetween: 15
    },
    768:{
      slidesPerView: 2,
      spaceBetween: 25
    },
    992:{
      slidesPerView: 3,
      spaceBetween: 25
    },
    1200:{
      slidesPerView: 3,
      spaceBetween: 25
    },
    1400:{
      slidesPerView:3
    },
    1600:{
      slidesPerView: 3
    },
  }
});
// Gallery Slider
var swiper = new Swiper(".gallery-slider1", {
  slidesPerView: 5,
  spaceBetween: 37,
  // centeredSlides: true,
  loop: true,
  speed:1500,
  autoplay: {
    delay: 2000,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    280:{
      slidesPerView: 2,
      spaceBetween: 15,
      centeredSlides: true
    },
    480:{
      slidesPerView: 2,
      spaceBetween: 15,
      centeredSlides: true
    },
    768:{
      slidesPerView: 3,
      spaceBetween: 25,
      centeredSlides: true
    },
    992:{
      slidesPerView: 4,
      spaceBetween: 25,
      centeredSlides: true
    },
    1200:{
      slidesPerView: 4,
      spaceBetween: 25,
      centeredSlides: true
    },
    1400:{
      slidesPerView:5
    },
    1600:{
      slidesPerView: 5
    },
  }
});


// Gallery Slider
var swiper = new Swiper(".h2-product-slider", {
  slidesPerView: 4,
  spaceBetween: 25,
  // centeredSlides: true,
  loop: true,
  speed:1500,
  autoplay: {
    delay: 2000,
  },

  breakpoints: {
    280:{
      slidesPerView: 1,
      spaceBetween: 15,
    },
    480:{
      slidesPerView: 1,
      spaceBetween: 15,
    },
    768:{
      slidesPerView: 2,
      spaceBetween: 20,
    },
    992:{
      slidesPerView: 3,
      spaceBetween: 20,
    },
    1200:{
      slidesPerView: 3,
      spaceBetween: 20,
    },
    1400:{
      slidesPerView:4,
      spaceBetween: 15,
    },
    1600:{
      slidesPerView: 4
    },
  }
});

// h2-reguler-items-slider
var swiper = new Swiper(".h2-reguler-items-slider", {
  slidesPerView: 4,
  spaceBetween: 25,
  loop: true,
  speed:1500,
  autoplay: {
    delay: 2000,
  },
  navigation: {
    nextEl: ".next-btn-3",
    prevEl: ".prev-btn-3",
  },

  breakpoints: {
    280:{
      slidesPerView: 1,
      spaceBetween: 15,
    },
    480:{
      slidesPerView: 2,
      spaceBetween: 15,
    },
    768:{
      slidesPerView: 2,
      spaceBetween: 20,
    },
    992:{
      slidesPerView: 3,
      spaceBetween: 20,
    },
    1200:{
      slidesPerView: 3,
      spaceBetween: 20,
    },
    1400:{
      slidesPerView:3,
      spaceBetween: 15,
    },
    1600:{
      slidesPerView: 3
    },
  }
});

// Gallery Slider
var swiper = new Swiper(".h2-gallery", {
  slidesPerView: 5,
  spaceBetween: 37,
  // centeredSlides: true,
  loop: true,
  speed:1500,
  autoplay: {
    delay: 2000,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    280:{
      slidesPerView: 1,
      spaceBetween: 35,
      centeredSlides: true
    },
    480:{
      slidesPerView: 2,
      spaceBetween: 35,
      centeredSlides: true
    },
    768:{
      slidesPerView: 2,
      spaceBetween: 35,
      centeredSlides: true
    },
    992:{
      slidesPerView: 3,
      spaceBetween: 25,
      centeredSlides: true
    },
    1200:{
      slidesPerView: 3,
      spaceBetween: 25,
      centeredSlides: true
    },
    1400:{
      slidesPerView: 5
    },
    1600:{
      slidesPerView: 5,
      centeredSlides: true
    },
  }
});


// Home2 Special Offer 
var swiper = new Swiper(".h3-spacial-offer-slider", {
  slidesPerView: 1,
  // spaceBetween: 15,
  effect: 'fade',
  fadeEffect: {
    crossFade: true
  },
  speed:1500,
  autoplay: {
    delay: 2000,
  },
  loop: true,
  pagination: {
    el: ".swiper-pagination-i",
    clickable: true,
  },
});
// h2-reguler-items-slider
var swiper = new Swiper(".h3-popular-food-slider", {
  slidesPerView: 4,
  spaceBetween: 55,
  loop: true,
  speed:1500,
  autoplay: {
    delay: 2000,
  },
  navigation: {
    nextEl: ".next-btn-3",
    prevEl: ".prev-btn-3",
  },

  breakpoints: {
    280:{
      slidesPerView: 1,
      spaceBetween: 15,
    },
    480:{
      slidesPerView: 2,
      spaceBetween: 15,
    },
    768:{
      slidesPerView: 2,
      spaceBetween: 20,
    },
    992:{
      slidesPerView: 3,
      spaceBetween: 20,
    },
    1200:{
      slidesPerView: 3,
      spaceBetween: 20,
    },
    1400:{
      slidesPerView:3,
      spaceBetween: 15,
    },
    1600:{
      slidesPerView: 3
    },
  }
});

// H3 testimonial-slider
var swiper = new Swiper(".home3-testimonial-slider", {
  slidesPerView: 4,
  spaceBetween: 25,
  loop: true,
  speed:1500,
  autoplay: {
    delay: 2000,
  },
  navigation: {
    nextEl: ".next-btn-4",
    prevEl: ".prev-btn-4",
  },

  breakpoints: {
    280:{
      slidesPerView: 1,
      spaceBetween: 15,
    },
    480:{
      slidesPerView: 2,
      spaceBetween: 15,
    },
    768:{
      slidesPerView: 2,
    },
    992:{
      slidesPerView: 3,
    },
    1200:{
      slidesPerView: 3,
    },
    1400:{
      slidesPerView:3,
    },
    1600:{
      slidesPerView: 3
    },
  }
});
// related-item-slider
var swiper = new Swiper(".related-item-sliders", {
  slidesPerView: 4,
  spaceBetween: 25,
  loop: true,
  speed:1500,
  autoplay: {
    delay: 2000,
  },
  navigation: {
    nextEl: ".next-btn-4",
    prevEl: ".prev-btn-4",
  },

  breakpoints: {
    280:{
      slidesPerView: 1,
      spaceBetween: 15,
    },
    480:{
      slidesPerView: 2,
      spaceBetween: 15,
    },
    768:{
      slidesPerView: 2,
    },
    992:{
      slidesPerView: 3,
    },
    1200:{
      slidesPerView: 3,
    },
    1400:{
      slidesPerView:3,
    },
    1600:{
      slidesPerView: 3
    },
  }
});

// init Masonry
var $grid = $('.grid').masonry({
  // options...
});
// layout Masonry after each image loads
$grid.imagesLoaded().progress( function() {
  $grid.masonry('layout');
});

}(jQuery));