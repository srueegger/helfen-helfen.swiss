(function($) {
	'use strict';

	/* Globale Variabeln */
	var has_close_cookie_banner = localStorage.getItem('cookieBanner');

	/* Slick Full Page Carousel laden */
	var fullSlider = $('.hh-full-carousel');
	fullSlider.slick(global_vars.slider_options);

	/* Slick Slider über das Mausrad steuern */
	fullSlider.on('wheel', (function(e) {
		e.preventDefault();
		if (e.originalEvent.deltaY < 0) {
		  $(this).slick('slickNext');
		} else {
		  $(this).slick('slickPrev');
		}
	}));

	/* Aktionen ausführen wenn auf den Hamburger Button gedrückt wird */
	var hamburger = $('.hamburger');
	hamburger.on('click', function() {
		hamburger.toggleClass('is-active');
		$('.main_menu').toggleClass('active');
		/* Body Scrollen ein und Auschalten */
		$('body').toggleClass('no-scroll');
	});

	/* Hauptmenü Desktop steuern */
	$('.menu-list:not(.menu-list-mobile) .menu-item.menu-item-has-children:not(.hovered-menu)').on('click', function(event) {
		event.preventDefault();
		$('.menu-item a').removeClass('underline');
		var dynamicContent = $('.dynamic-menu-content');
		var $this = $(this);
		var subMenu;
		/* Untermenü finden */
		subMenu = $this.find('.sub-menu').html();
		/* Inhalt im dynamischen Content zeichnen */
		dynamicContent.html('<ul class="list-unstyled custom-sub-menu">'+subMenu+'</ul>');
		dynamicContent.addClass('active');
	});

	/* News in den dynamischen Inhalt füllen */
	$('.menu-news').on('mouseover', function() {
		setTimeout(function() {
			var dynamicContent = $('.dynamic-menu-content');
			var newsContent = $('#menuNewsContent').html();
			dynamicContent.html('');
			dynamicContent.removeClass('active');
			dynamicContent.html(newsContent);
			dynamicContent.addClass('active');
		}, 150);
	});

	/* Dynamischer Content leeren, wenn nichts relevantes selektiert wird */
	$('.menu-item:not(.menu-item-has-children)').on('mouseover', function () {
		var dynamicContent = $('.dynamic-menu-content');
		dynamicContent.removeClass('active');
		dynamicContent.html('');
		$('.menu-item a').removeClass('underline');
		setTimeout(function() {
			dynamicContent.html('');
			dynamicContent.removeClass('active');
			$('.menu-item a').removeClass('underline');
		}, 100);
	});

	/* Counter Block: Counter aktivieren */
	$('.js_counter').rCounter({
		duration: 40
	});

	/* In den Full-Image Block den ScrollDown Pfeil einfügen */
	$('.wp-block-cover.fullpage, .wp-block-video.fullpage, .videoblock.fullpage').append(global_vars.txt.arrow_bottom);

	/* 100vh herunterscrollen nach Klick auf das Scroll Down Icon */
	$(document).on('click', '.fullpageArrowBottom', function() {
		scrollDown();
	});

	/* Resize Funktion für 100 vh und 100 vw Relationen */
	function resize() {
		var vheight = $(window).height();
		var vwidth = $(window).width();
		$('.wp-block-cover.fullpage').css({
			'height': '100vh',
			'min-height': '100vh',
			'width': vwidth 
		});
	};
	resize();

	/* Beim Scrollen die Sizes jeweils neu berechnen */
	$(window).resize(function() {
		resize();
	});

	// Scroll Down Funktion für Button auf fullpage Bild
	function scrollDown() {
		var vheight = $(window).height();
		$('html, body').animate({
			scrollTop: (Math.floor($(window).scrollTop() / vheight)+1) * vheight
		}, 500);
	};

	/* Prüfen ob auf der Seite einen Countdown angezeigt werden muss, falls ja CountDown ausgeben */
	if($('div[data-countdownto]').length) {
		$('div[data-countdownto]').each(function() {
			var countdownContainer = $(this);
			var countdownDateTime = countdownContainer.data('countdownto');
			// Set the date we're counting down to
			var countDownDate = new Date(countdownDateTime).getTime();
			// Update the count down every 1 second
			var x = setInterval(function() {
				var now = new Date().getTime();

				// Find the distance between now and the count down date
				var distance = countDownDate - now;

				// Time calculations for days, hours, minutes and seconds
				var days = Math.floor(distance / (1000 * 60 * 60 * 24));
				var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				var seconds = Math.floor((distance % (1000 * 60)) / 1000);
				/* Counter ausgeben */
				$('.countdownValue.days').text(days);
				$('.countdownValue.hours').text(hours);
				$('.countdownValue.minutes').text(minutes);
				$('.countdownValue.seconds').text(seconds);
			}, 1000);
		});
	}

	/* Bei einem Block wo es die Klasse "inline-columns" gibt müssen die Container Klassen entfernt werden */
	var inline_columns = $('.inline-columns');
	if(inline_columns.length) {
		inline_columns.find('.container').removeClass('container').find('.row').removeClass('row justify-content-center').find('.col-12').removeClass('col-12 col-lg-9');
	}

	/* Prüfen ob die Url das Wort Seite enthält - falls ja zum Inhaltsbereich scrollen */
	var current_url = window.location.href;
	if(current_url.indexOf('seite') > -1 && current_url.indexOf('news')) {
		scrollDown();
	}

	/* Wenn auf eine Newsvorschau im Menü geklickt wird, Link zur entsprechen News öffnen */
	$(document).on('click', '.news-item', function() {
		var go_to_link = $(this).data('goto');
		window.location.href = go_to_link;
	});

	/* Mobile: Bei Klick auf Hauptmenü - Untermenü anzeigen */
	$(document).on('click', 'ul.menu-list.menu-list-mobile .menu-item.menu-item-has-children > a:not(.colored)', function(event) {
		/* Browser Aktionen stoppen */
		event.preventDefault();
		/* Das passende Submenü herausfinden */
		var subMenu = $(this).parent().find('.sub-menu');
		/* Alle farbigen Menüpunkte entfernen */
		$('.menu-item a').removeClass('colored');
		/* Alle Submenüs schliessen */
		$('.sub-menu').removeClass('open');
		/* Aktiver Menüpnkt einfärben */
		$(this).addClass('colored');
		/* Das ausgewählte Submenü öffnen */
		subMenu.addClass('open');
		/* Social Media Buttons ausblenden */
		$('.social-media-container').hide();
	});

	/* Mobile: Bei Klick auf Hauptmenü - bereits offenes Menü - Menü schliessen */
	$(document).on('click', 'ul.menu-list.menu-list-mobile .menu-item.menu-item-has-children > a.colored', function(event) {
		/* Browser Aktionen stoppen */
		event.preventDefault();
		/* Alle farbigen Menüpunkte entfernen */
		$('.menu-item a').removeClass('colored');
		/* Alle Submenüs schliessen */
		$('.sub-menu').removeClass('open');
		/* Social Media Buttons einblenden */
		$('.social-media-container').show();
	});

	/* Formular Button beim Kontaktformular anpassen */
	$(document).on('gform_post_render', function(event, form_id, current_page){
		/* Prüfen ob wir auf der Kontaktseite sein */
		if($('.block-contactform').length) {
			/* Wir sind auf der Kontaktseite - Formular Button ändern wenn der Hintergrund blau (bg-primary) ist */
			if($('.block-contactform').hasClass('bg-primary')) {
				/* Der Hintergrund ist blau => Button gelb machen */
				var btn = $('.gform_footer .btn');
				btn.removeClass('btn-primary');
				btn.addClass('btn-secondary');
			}
		}
	});

	/* Alle vorhandenen Modal Fenster öffnen */
	var modal_window_object = $('.helfen-helfen-modal');
	if(modal_window_object.length) {
		modal_window_object.each( function() {
			var modal_id = $(this).attr('id');
			/* Prüfen ob das Fenster geöffnet werden darf, falls ja öffnen */
			if(sessionStorage.getItem('modal-' + modal_id) != 'notopen') {
				$( '#' + modal_id ).modal('show');
			}
		});
	}

	/* Prüfen ob das schliessen des Modal Window gespeichert werden musss */
	$('.modal').on('hidden.bs.modal', function () {
		var save_close = $(this).data('saveclose');
		if(save_close == 'yes') {
			var modal_id = $(this).attr('id');
			sessionStorage.setItem('modal-' + modal_id, 'notopen');
		}
	});

	/* Bei Klick auf Scroll to Top Icon nach oben scrollen */
	$('#scrollToTop').on('click', function() {
		$('html, body').animate({ scrollTop: 0 }, 'slow');
	});

	/* GravityForms Changes after Page Load */
	$(document).on('gform_post_render', function(event, form_id, current_page) {
		$('.gform_drop_instructions').text(global_vars.txt.gf_change_upload);
	});

	/* Gutenberg Abstandhalter für mobile Ansicht halbieren */
	if($(window).width() <= 576) {
		hh_half_gutenberg_spacers();
	}

	function hh_half_gutenberg_spacers() {
		$('.wp-block-spacer').each(function() {
			var spacer_height = $(this).outerHeight();
			$(this).css('height', spacer_height / 2);
		});
	}

	/* Falls kein LocaStorage besteht Cookie Banner anzeigen */
	var cookie_banner = $('#cookieBanner');
	if(has_close_cookie_banner != 1) {
		cookie_banner.addClass('show');
	}

	/* Cookie Banner schliessen */
	$('#closeCookieBanner').on('click', function() {
		/* Schliessen des Cookie Banner in der localstorage speichern */
		localStorage.setItem('cookieBanner', 1)
		/* Cookie Banner schliessen */
		cookie_banner.removeClass('show');
	});

	/* Neues Newsletter Formular set Placeholder */
	$('#rm-firstname').attr('placeholder', 'Vorname');
	$('#rm-lastname').attr('placeholder', 'Nachname');
	$('#rm-email').attr('placeholder', 'E-Mail-Adresse');
	$('#rm-firstname').attr('required', 'required');
	$('#rm-lastname').attr('required', 'required');

	/* Swiper */
	/* Init History Slider */
  var timelineSwiper = new Swiper ('.timeline .swiper-container', {
    direction: 'vertical',
    loop: false,
    speed: 1600,
    dynamicBullets: true,
    pagination: {
      el: '.swiper-pagination',
      renderBullet: function (index, className) {
        var year = document.querySelectorAll('.swiper-slide')[index].getAttribute('data-year');
        return '<span class="' + className + '"><span class="d-inline-block mr-3">' + year + '</span></span>';
      },
      clickable: true,
    },
    navigation: {
      nextEl: '.slider-next-element'
    }
  });

	/* Video Block - zwischen den Videos wechseln */
	$( '.video_navigation li' ).on( 'click', function() {
		let show = $( this ).data( 'show' );
		let videos = $( '.videoblock video' );
		videos.hide();
		videos.trigger('pause');
		$( show ).show();
		$( show ).trigger('play');
	} );

	/* Column Fix */
	$( '.wp-block-column' ).each( function( index, element ) {
		$( element ).find( '.row' ).removeClass( 'justify-content-center' );
		$( element ).find( '.col-lg-9' ).removeClass( 'col-lg-9 col-12' );
	} );
	$( '.wp-block-columns' ).parent().removeClass( 'col-lg-9' );

	/* Bild Polyfill für IE aktivieren */
	objectFitPolyfill();
})(jQuery);