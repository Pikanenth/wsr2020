(function($){
	"use strict";	
    $(document).ready(function() {

        if ($('body').length ) { $('body').fitVids(); }
        $('select').chosen();
        
        // Function DosisLite
        init_carousel();
        dosislite_mainmenu();

        // Nav Sidebar
        $(document).on('click','.navbar-touch',function( event ){
            event.stopPropagation();
            $('body').addClass('open-navsidebar');
            return false;
        });
        
        // Menu Touch        
        $('.body-overlay').on('click',function() {
            $('body').removeClass('open-menutouch');
            $('body').removeClass('open-navsidebar');
        });

        $(document).on('click','.menu-touch',function( event ){
            event.stopPropagation();
            $('body').addClass('open-menutouch');
            return false;
        });
        
        //Nav Search
         $(document).on('click','.navbar-search',function(){
            $(this).parent().addClass('search-active');
            return false;
        });
        
        $(document).on('click','.close-search',function(){
            $(this).closest('.navbar-col').removeClass('search-active');
            return false;
        });


        //Submenu 
        var _subMenu = $('.main-menu-horizontal .dosislite-main-menu > li > .sub-menu');
        _subMenu.each(function(){
            var _widthSub = $(this).outerWidth(),
                _widthContainer = $('.main-wrapper-boxed').outerWidth(),
                offsetContent = ($(window).width() - _widthContainer)/2;
            
            var offsetLeft = $(this).offset().left,
                offsetRight = $(window).width() - offsetLeft;

            var _rightPos =  offsetRight - offsetContent;

            if(_rightPos < _widthSub){
                var _left = (_widthSub - _rightPos) + 50;
                console.log(_left);
                $(this).css({
                    "left": '-'+_left+'px'
                });
                $(this).find('.sub-menu').css({
                    "left": "auto",
                    "right": "100%"
                });
            }
        })

    });

    /* ---------------------------------------------
     Owl carousel
    --------------------------------------------- */
    function init_carousel()
    {
        $('.owl-carousel').each(function(){
            var config = $(this).data();
            config.navText = ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'];
        
            var animateOut = $(this).data('animateout');
            var animateIn = $(this).data('animatein');
            if(typeof animateOut != 'undefined' ){
              config.animateOut = animateOut;
            }
            if(typeof animateIn != 'undefined' ){
              config.animateIn = animateIn;
            }
            
            config.smartSpeed = 1000;

            var owl = $(this);
            owl.owlCarousel(config);

        });

        document.onkeyup = PresTab;
    }

    
    function PresTab()
    {
        if ( ! $('ul.dosislite-main-menu > li a, div.dosislite-main-menu > ul > li a').is(':focus') ) {
            $('.dosislite-main-menu li').removeClass('is-focus');
        }
    }

    //Menu 
    function dosislite_mainmenu()
    {
        //Focus
        $('ul.dosislite-main-menu > li a, div.dosislite-main-menu > ul > li a').on('focus',function(){
            var prevLi = $(this).parents('li').last().prev(),
                currentLi = $(this).parents('li').last(),
                nextLi = $(this).parents('li').last().next();

                currentLi.addClass('is-focus');
                if (prevLi.length > 0 && prevLi.hasClass('is-focus') ) {
                    prevLi.removeClass('is-focus');
                }
                if (nextLi.length > 0 && nextLi.hasClass('is-focus') ) {
                    nextLi.removeClass('is-focus');
                }

            var currentFocus = $(this).parent(),
                prevCurrent = currentFocus.prev(),
                nextCurrent = currentFocus.next();

                currentFocus.addClass('is-focus');
                if (prevCurrent.length > 0 && prevCurrent.hasClass('is-focus') ) {
                    prevCurrent.removeClass('is-focus');
                }
                if (nextCurrent.length > 0 && nextCurrent.hasClass('is-focus') ) {
                    nextCurrent.removeClass('is-focus');
                }

        })

        //Hover
        $('.dosislite-main-menu  li.menu-item-has-children, .dosislite-main-menu  li.page_item_has_children').hover(function(){
            $(this).addClass('is-hover');
        },function(){
            $(this).removeClass('is-hover');
        });

        //Click
        $('.dosislite-main-menu  .menu-item-has-children > a, .dosislite-main-menu .page_item_has_children > a').on('click',function(e){
            if ($(this).next('ul').is(':visible')) {
                $(this).next('ul').slideUp('100');
                $(this).removeClass('active');
            } else {
                $(this).closest('ul').children('li').children('.active').next('ul').slideUp('100');
                $(this).closest('ul').children('li').children('.active').removeClass('active');
                $(this).next().slideToggle('200');
                $(this).addClass('active');
            }
            return false;
        });

    }
    
    
})(jQuery);
