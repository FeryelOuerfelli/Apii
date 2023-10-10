(()=>{var __webpack_exports__={};/*!******************************************!*\
!*** ./resources/js/pages/slick.init.js ***!
\******************************************/if($('.specialities-slider').length>0){$('.specialities-slider').slick({dots:true,autoplay:false,infinite:true,variableWidth:true,prevArrow:false,nextArrow:false});}
if($('.testimonial-slider').length>0){$('.testimonial-slider').slick({dots:true,autoplay:false,infinite:true,prevArrow:false,nextArrow:false,slidesToShow:3,slidesToScroll:1,responsive:[{breakpoint:992,settings:{slidesToShow:3}},{breakpoint:776,settings:{slidesToShow:2}},{breakpoint:567,settings:{slidesToShow:1}}]});}
if($('.doctor-slider').length>0){$('.doctor-slider').slick({dots:false,autoplay:false,infinite:true,variableWidth:true});}
if($('.features-slider').length>0){$('.features-slider').slick({dots:true,infinite:true,centerMode:true,slidesToShow:3,speed:500,variableWidth:true,arrows:false,autoplay:false,responsive:[{breakpoint:992,settings:{slidesToShow:1}}]});}
if($('.features-slider1').length==1){$('.features-slider1').slick({dots:false,infinite:true,centerMode:false,slidesToShow:1,speed:500,variableWidth:true,arrows:true,autoplay:false,responsive:[{breakpoint:992,settings:{slidesToShow:1}}]});}
if($('.slider-1').length>0){$('.slider-1').slick();}})();