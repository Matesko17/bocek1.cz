import '../css/main.scss';
import $ from 'jquery';

import * as contact_form from './../plugins/contact_form/contact_form.js';
import './../plugins/created_by_dm/index.js';

//Contact form
contact_form.contact_form_init();

//Google GTM dataLayer event
$(document).on('submit', '.mainpage_contact_form form', function() {
  window.dataLayer.push({
    'event': 'customer_inquiry_sent',
  });
});

$(function() {
  $('#header_handler .nav_inner > ul > li').on('mouseover', function() {
    if ($(this).find('ul').length === 0) {
      return;
    }

    clearTimeout($(this).data('timer'));
    $(this).addClass('open');
  }).on('mouseout', function() {
    if ($(this).find('ul').length === 0) {
      return;
    }

    const $this = $(this);
    const timer = setTimeout(function() {
      if (!$this.is(':hover') && !$this.find('ul').is(':hover')) {
        $this.removeClass('open');
      }
    }, 300);
    $this.data('timer', timer);
  });

  $('#header_handler .nav_inner > ul > li > ul').on('mouseover', function() {
    clearTimeout($(this).closest('li').data('timer'));
  }).on('mouseout', function() {
    const $parentLi = $(this).closest('li');
    const timer = setTimeout(function() {
      if (!$parentLi.is(':hover') && !$parentLi.find('ul').is(':hover')) {
        $parentLi.removeClass('open');
      }
    }, 300);
    $parentLi.data('timer', timer);
  });
});