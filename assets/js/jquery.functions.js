//import Flickity from 'flickity';
var Flickity = require('flickity');
require('flickity-imagesloaded');

(function($,sr){

  // debouncing function from John Hann
  // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
  var debounce = function (func, threshold, execAsap) {
      var timeout;

      return function debounced () {
          var obj = this, args = arguments;
          function delayed () {
              if (!execAsap)
                  func.apply(obj, args);
              timeout = null;
          };

          if (timeout)
              clearTimeout(timeout);
          else if (execAsap)
              func.apply(obj, args);

          timeout = setTimeout(delayed, threshold || 100);
      };
  }
  // smartresize 
  jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');

(function($) {

  var jQueryBridget = require('jquery-bridget');
  var InfiniteScroll = require('infinite-scroll');
  //var Flickity = require('flickity');

  jQueryBridget( 'infiniteScroll', InfiniteScroll, $ );
  //jQueryBridget( 'flickity', Flickity, $ );

  // var storyC = $('.story-carousel').flickity({
  //   contain: true,
  //   pageDots: false,
  //   cellAlign: 'center',
  // });

  // var newsC = $('.news-carousel').flickity({
  //   contain: true,
  //   pageDots: false,
  //   cellAlign: 'center',
  // });

  if($('.news-carousel').length) {
    var flkty = new Flickity('.news-carousel', {
      contain: true,
      pageDots: false,
      imagesLoaded: true,
      cellAlign: 'center',
      on: {
        ready: function() {
          $('.news-carousel .flickity-prev-next-button').wrapAll('<div class="flickity-btn-container" />');
          this.resize();
        }
      }
    });
  }

  if($('.story-carousel').length) {
    var flkty = new Flickity('.story-carousel', {
      contain: true,
      pageDots: false,
      imagesLoaded: true,
      cellAlign: 'center',
      on: {
        ready: function() {
          $('.story-carousel .flickity-prev-next-button').wrapAll('<div class="flickity-btn-container" />');
          this.resize();
        }
      }
    });
  }

  if($('.study-carousel').length) {
    var flkty = new Flickity('.study-carousel', {
      contain: true,
      pageDots: true,
      prevNextButtons: true,
      imagesLoaded: true,
      cellAlign: 'center',
      on: {
        ready: function() {
          this.resize();
        }
      }
    });
  }



  
  $(window).smartresize(function(e){
    // console.log('resized')
    if(flkty) {
      flkty.resize();
      // console.log('flickity')
    }
  });

  // $('#field_17_7 input[type=checkbox]').addClass('form-checkbox');
  // $('#field_17_6').appendTo('#field_17_5');
  // $('#field_17_5 > .ginput_container_text, #field_17_6').wrapAll('<div class="form-wrap" />');



  $(document).bind('gform_post_render', function(e, formId, current_page) {

    if(formId == 19) {
      //console.log('called again')
      $('#field_19_7 input[type=checkbox]').addClass('form-checkbox');
      $('#field_19_6').appendTo('#field_19_5');
      $('#field_19_5 .ginput_container_text, #field_19_6').wrapAll('<div class="form-wrap" />');
    }
  })

  if($('#state-hero').length) {
    var stateName = $('#state-hero').data("state");
    //console.log(stateName);
    if(stateName.length) {
      $('#field_19_6 select').val(stateName).change();
    }
  }


  if ($(".video-section").length > 0) {
    var $video = $(".video-section video");
    var desktop = $(window).width() >= 1024 || !$video.attr("data-mobile-src");
    var poster = desktop
      ? $video.attr("data-desktop-poster")
      : $video.attr("data-mobile-poster");
    $video.attr("poster", poster);
    $(".video-section").css("background-image", "url(" + poster + ")");
    $('<source type="video/mp4">')
      .attr(
        "src",
        desktop
          ? $video.attr("data-desktop-src")
          : $video.attr("data-mobile-src")
      )
      .appendTo($video);
    var interval = setInterval(function() {
      if ($video.get(0).readyState === 4) {
        // $video.get(0).play()
        var video = document.getElementById("video");
        video.play();

        clearInterval(interval);
      }
    }, 400);
  }

  $(document).ready(function() {

    if ($(".story-hero .video-container").length > 0) {
      $(".story-hero .video-container iframe").attr("id", "video");
      var iframe = $(".story-hero .video-container iframe");
      iframe.attr("src", iframe.attr("src") + "?enablejsapi=1");
      function callPlayer(func, args) {
        var i = 0,
          iframe = document.getElementById("video");
        src = "";
        if (iframe) {
          src = iframe.getAttribute("src");
          if (src && src.indexOf("youtube.com/embed") !== -1) {
            iframe.contentWindow.postMessage(
              JSON.stringify({
                event: "command",
                func: func,
                args: args || []
              }),
              "*"
            );
          }
        }
      }

      $("#toggle-video").click(function(e) {
        e.preventDefault();
        callPlayer("playVideo");
        $(".story-hero .content-container").fadeOut();
      });
    }

    if ($(window).width() <= 1024) {
      $(window).scroll(function() {
        if ($(window).scrollTop() > $(window).height()) {
          $(".back-top").show();
        } else {
          $(".back-top").hide();
        }
      });
      $(window).trigger("scroll");
    }

    $(".back-top").click(function(e) {
      e.preventDefault();
      $([document.documentElement, document.body]).animate(
        {
          scrollTop: 0
        },
        400
      );
    });

    $(".voice-letter").click(function(e) {
      e.preventDefault();
      $([document.documentElement, document.body]).animate(
        {
          scrollTop: $($(this).attr("href")).offset().top
        },
        400
      );
    });

    $(".team-section-link a").click(function(e) {
      e.preventDefault();
      $([document.documentElement, document.body]).animate(
        {
          scrollTop: $($(this).attr("href")).offset().top
        },
        400
      );
    });

    function addLoadMore() {
      var $pagination = $(".archive-pagination");
      if ($pagination.length > 0 && $(".pagination-next").length > 0) {
        $pagination.addClass("ajax-load-pagination");

        $(".pagination-next, .pagination-previous").hide();

        $('<div class="pagination-loading"></div>').appendTo(
          ".archive-pagination"
        );
        var loadMoreText =
          $pagination.find("a").attr("data-more-text") || "Load More";
        var $loadMoreButton = $('<a class="button">' + loadMoreText + "</a>");
        $('<div class="pagination-load"></div>')
          .append($loadMoreButton)
          .appendTo(".archive-pagination");
        var nextUrl = $(".pagination-next a").attr("href");
        $loadMoreButton.attr("href", nextUrl);

        $loadMoreButton.click(function(e) {
          e.preventDefault();
          $pagination.addClass("loading");
          $("<div></div>").load(
            $loadMoreButton.attr("href") + " .content-sidebar-wrap",
            function() {
              var $entries = $(this)
                .find(".content > .entry")
                .css("opacity", 0);
              $(".content").append($entries);
              $entries.fadeTo(300, 1);
              $pagination.removeClass("loading");
              var $next = $(this).find(".pagination-next a");
              if ($next.length > 0) {
                $loadMoreButton.attr("href", $next.attr("href"));
                $loadMoreButton.text("Load More");
              } else {
                $pagination.remove();
              }
            }
          );
        });
      }
    }
    addLoadMore();

    if ($(".reports-search").length > 0) {
      var $input = $(".reports-search .search-input");
      var text = "";
      $(".reports-search button").click(function(e) {
        e.preventDefault();
        searchReports();
      });
      $input.keypress(function(e) {
        if (e.which === 13) {
          e.preventDefault();
          searchReports();
        }
      });
      function searchReports() {
        if (text === $input.val().trim()) {
          return;
        }
        text = $input.val().trim();
        $(".content").html('<div class="report-search-loading"></div>');
        if (text) {
          $(".archive-pagination").hide();
        }
        var url = window.location.href.split("?")[0];
        if (text) {
          url += "?rs=" + encodeURIComponent(text);
        }
        $("<div></div>").load(url + " .content-sidebar-wrap", function() {
          var $entries = $(this)
            .find(".content > .entry")
            .css("opacity", 0);
          $(".content").html($entries);
          $entries.fadeTo(300, 1);
          if (!text) {
            $(".archive-pagination").show();
          }
        });
      }
    }

    $('a[href^="mailto:"]').each(function() {
      if (!$(this).attr("target")) {
        $(this).attr("target", "_blank");
      }
    });

    $(".nav-primary .menu").slicknav({
      label: "",
      allowParentLinks: true,
      openedSymbol: '<i class="fas fa-chevron-down"></i>',
      closedSymbol: '<i class="fas fa-chevron-right"></i>',
      appendTo: ".site-header > .wrap"
    });
    $(".header-right .membership-buttons")
      .clone()
      .appendTo(".slicknav_nav");

    $(document).delegate(".ss-tr", "click", function(e) {
      e.preventDefault();
      var url = $(this).attr("href");
      var text = $(this).data("text");
      var x = parseInt($(window).width() / 2) - 300;
      var y = parseInt($(window).height() / 2) - 200;
      window.open(
        "https://twitter.com/intent/tweet?&original_referer=" +
          url +
          "&text=" +
          text +
          "&tw_p=tweetbutton&url=" +
          url,
        "Twitter",
        "width=600,height=400,left=0,top=" + y
      );
    });

    $(document).delegate(".ss-fk", "click", function(e) {
      var href = $(this).prop("href");
      FB.ui(
        {
          method: "share",
          href: href
        },
        function(response) {}
      );
      e.preventDefault();
    });

    var getUrlParameter = function getUrlParameter(sParam) {
      var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split("&"),
        sParameterName,
        i;

      for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split("=");

        if (sParameterName[0] === sParam) {
          return sParameterName[1] === undefined
            ? true
            : decodeURIComponent(sParameterName[1]);
        }
      }
    };

    function addhttp(url) {
      if (!/^(?:f|ht)tps?\:\/\//.test(url)) {
        url = "http://" + url;
      }
      return url;
    }

    // setTimeout(function(){
    //$( document ).ready(function() {git
    // if($('#member-feed')){
    //   //console.log("Got HERE");
    //   var url = dir.url + '/lib/invisionsoft.php';
    //   var code = getUrlParameter('code');
    //   //console.log(url);
    //   //console.log(code);
    //   $.ajax({
    //     url : url,
    //     data: (typeof code !== "undefined") ? {data : code} : {data: 'request'},
    //     //data: {data: 0},
    //     type: "POST",
    //   }).success(function(data){
    //     //console.log(data);
    //     //console.log(JSON.parse(data));
    //     //$('#member-feed').html(data);
    //     parsed = JSON.parse(data);
    //     //console.log(parsed.data);
    //     //console.log(JSON.parse(data));

    //     $('#loading').hide();
    //     if(parsed.status == "success") {
    //       //console.log("got here");

    //       $.each(parsed.data, function(k, v) {
    //         //console.log(typeof v["Contact.Website"]);
    //         var url = addhttp(v["Contact.Website"]);
    //         $('#member-feed').append(
    //           '<a target="_blank"' + ((typeof v["Contact.Website"] !== 'undefined') ? 'href="' + url : "") + '"><div class="member-item">' + v["Contact.Company"] + '</div>' + ((typeof v["Contact.Website"] !== 'undefined') ? '<svg class="member-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-external-link"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>' : "") + '</a>'
    //         );
    //     });

    //     }
    //     else {
    //       $('#member-feed').append('<a href="' + parsed.reauthorize +'">Click here to reauthorize with Infusionsoft</a>')
    //     }

    //   }).error(function(){
    //     $('#loading').hide();
    //     $('#member-feed').html('There was an error retrieving this list.');
    //   });
    // }

    //  //}, 2000);

    // var originalText = $("#loading").text(),
    // i  = 0;
    // setInterval(function() {
    //   $("#loading").append(".");
    //   i++;

    //   if(i == 4)
    //   {
    //       $("#loading").html(originalText);
    //       i = 0;
    //   }
    // }, 500);

    // if($('#member-feed')){
    //   //console.log('ajax ajax') 
    //   $.ajax({
    //       type:"GET",
    //       url: ajax_url.ajax_url,
    //       data: {
    //           action:'get_salesforce', 
    //       },
    //       success:function(data){
    //         //console.log(data);
    //         $('#member-feed').html(' ');
    //         $('#member-feed').append(data)
    //         infinite();
    //       },
    //       error: function (e) {
    //         console.log(e);
    //       } 
    //   });
    // };

    function infinite() {
      var infinite = $('#member-feed').infiniteScroll({
        // options
        path: '{{#}}',
        history: false,
        scrollThreshold: 3000,
      });

      var hitBottom = false;
      $(infinite).on( 'scrollThreshold.infiniteScroll', function( event ) {
        console.log('Scroll at bottom');

        if(!$('.sf-load-container').length) {
          //console.log('scroll destory');
          infinite.infiniteScroll('destroy');
          $('#loading').hide();
        }

        if(!hitBottom && $('.sf-load-container').length) {
          hitBottom = true;
          var url=$('#salesforce-load').val();
          $('.sf-load-container').hide();
          $.ajax({
            type:"POST",
            url: ajax_url.ajax_url,
            data: {
              action:'get_salesforce',
              load_url:url
            },
            success:function(data){
              //console.log(data);
              // $('#member-feed').html(' ');
              $('.sf-load-container').detach();
              $('#member-feed').append(data)
              //window.setTimeout(() => { hitBottom = false; }, 1000)
              hitBottom = false;
            },
            error: function (e) {
              console.log(e);
            } 
          });
        }

      });
    }

    $('#member-feed').on('click', '#salesforce-load', function() {
      var url=$(this).val();
      $(this).hide();
      //console.log(url);
      $.ajax({
        type:"POST",
        url: ajax_url.ajax_url,
        data: {
          action:'get_salesforce',
          load_url:url
        },
        success:function(data){
          //console.log(data);
          // $('#member-feed').html(' ');
          $('.sf-load-container').detach();
          $('#member-feed').append(data)
        },
        error: function (e) {
          console.log(e);
        } 
    });

    })

    //Virus JS
    function GetURLParameter(sParam) {
      var sPageURL = window.location.search.substring(1);
      var sURLVariables = sPageURL.split("&");
      for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split("=");
        if (sParameterName[0] == sParam) {
          return sParameterName[1];
        }
      }
    }

    var term = GetURLParameter("state");
    if(term) {
      $("#state-fund").val(term);
    }

    $('#aid-type').change(function() {
      //console.log('Got here');
      //console.log($(this).val())
      if($(this).val() == 'all') {
        $('#federal-aid').fadeIn();
        $('#state-aid').fadeIn();
        $('#private-aid').fadeIn();
      }
      if($(this).val()  == 'federal') {
        //console.log('Got Here')
        $('#federal-aid').fadeIn();
        $('#state-aid').fadeOut();
        $('#private-aid').fadeOut();
      }
      if($(this).val()  == 'state') {
        $('#federal-aid').fadeOut();
        $('#state-aid').fadeIn();
        $('#private-aid').fadeOut();
      }
      if($(this).val()  == 'private') {
        $('#federal-aid').fadeOut();
        $('#state-aid').fadeOut();
        $('#private-aid').fadeIn();
      }
    });

    $('#aid-filter').change(function() {
      console.log($(this).val());
      if($(this).val() == 'all') {
        $('#aid-item').fadeIn();
      }
      if($(this).val() == 'loan') {
        $('.loan').fadeIn();
        $('.grant').fadeOut();
        $('.loan.grant').fadeIn();

      }
      if($(this).val() == 'grant') {
        $('.loan').fadeOut();
        $('.grant').fadeIn();
        $('.loan.grant').fadeIn();
      }
    });

    $('#all-btn').click(function() {
      $('#aid-item').fadeIn();
      $('#type-btns button').removeClass('type-active')
      $(this).addClass('type-active')
    });

    $('#grant-btn').click(function() {
      $('.loan').fadeOut();
      $('.grant').fadeIn();
      $('.loan.grant').fadeIn();
      $('#type-btns button').removeClass('type-active')
      $(this).addClass('type-active')
    });


    $('#loan-btn').click(function() {
      $('.loan').fadeIn();
      $('.grant').fadeOut();
      $('.loan.grant').fadeIn();
            $('#type-btns button').removeClass('type-active')
      $(this).addClass('type-active')
    });

    const changeNav = (entries, observer) => {
      entries.forEach((entry) => {
        // verify the element is intersecting
        //console.log(entry);
        // if(entry.isIntersecting && entry.intersectionRatio >= 0.55) {
        //   // remove old active class
        //   if($('.impact-nav ul li a.active').hasClass('active')) {
        //     document.querySelector('div.impact-nav ul li a.active').classList.remove('active');
        //   }
        //   // get id of the intersecting section
        //   var id = entry.target.getAttribute('id');
        //   //console.log(id);
        //   // find matching link & add appropriate class
        //   var newLink = document.querySelector(`[href="#${id}"]`).classList.add('active');

          var id = entry.target.getAttribute('id');
          if (entry.isIntersecting) {
            $('#resource-nav a').removeClass('aid-active')
            $(`[href="#${id}"]`).addClass('aid-active');
            //entry.target.classList.add('active');
          // Otherwise, remove the “active” class
          }
        // }
      });
    }

    // init the observer
    const options = {
      root: null,
      rootMargin: '-250px 0px -50%',
    }

    const observer = new IntersectionObserver(changeNav, options);

    // target the elements to be observed
    const sections = document.querySelectorAll('div.resource-aid');
    //console.log(sections);
    sections.forEach((section) => {
      //console.log(section);
      observer.observe(section);
    });

    $("a[href*=\\#]:not([href=\\#])").click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
          || location.hostname == this.hostname) 
      {
        
        var target = $(this.hash),
        headerHeight = $(".banner").height() + 50; // Get fixed header height
              
        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                
        if (target.length) 
        {
          $('html,body').animate({
            scrollTop: target.offset().top - headerHeight
          }, 'slow');
          return false;
        }
      }
    });

    // Standup Page

    var standupstate = GetURLParameter("state");
    if(standupstate) {
      $("#input_14_3").val(standupstate);
      $("#standup-companies").removeClass('blur');
      $("#letter-container > div").removeClass('blur');
    }

    // if(comp = GetURLParameter("comp")) {
    //   $("#input_14_1").val(decodeURIComponent(comp));
    // }

    // if(city = GetURLParameter("city")) {
    //   $("#input_14_2").val(decodeURIComponent(city));
    // }

    // if(first = GetURLParameter("first")) {
    //   $("#input_14_4").val(decodeURIComponent(first));
    // }

    // if(last = GetURLParameter("last")) {
    //   $("#input_14_5").val(decodeURIComponent(last));
    // }

    // if(email = GetURLParameter("email")) {
    //   $("#input_14_6").val(decodeURIComponent(email));
    // }

    // $("#input_14_3").change(function() {
    //   var comp = $("#input_14_1").val();
    //   var city = $("#input_14_2").val();
    //   var first = $("#input_14_4").val();
    //   var last = $("#input_14_5").val();
    //   var email = $("#input_14_6").val();
    //   var state = $(this).val();
    //   window.location.href = '/standupforsmallbusiness/?state=' + state + '&comp=' + comp + '&city=' + city + '&first=' + first + '&last=' + last + '&email=' + email + '#standup-state';
    // });

    // $('#gform_14 .gform_footer').appendTo('#gform_14 .gform_fields');


    $(".member-box").delegate(".ajax-load", 'click', function(e){
      e.preventDefault();
      var page =$(this).data("page");
      $.ajax({
          type:"POST",
          url: ajax_url.ajax_url,
          data: {
              action:'resource_load_more', 
              page: page,
          },
          beforeSend: function() {
            $('.ajax-loading').show();
            $('.ajax-load').hide();
          },  
          success:function(data){
            //var result = JSON.parse(data)
            //console.log(result.view)
            //console.log(data);
            $('.ajax-load-container').remove();
            $('#member-feed').append(data);
            // $('.rp-container').append(result.view);
            // if(result.button) {
            //   // $('.ajax-load').show();
            //   $('.ajax-load').data('page', result.button);
            // }
            // else {
            //   $('.ajax-load').hide();
            // }
          },
          complete:function() {
            $('.ajax-loading').hide();
          },  
          error: function (req, e) {
            console.log(JSON.stringify(req));
          } 
      });
    });

  });



})(jQuery);

Flickity.prototype._createResizeClass = function() {
  this.element.classList.add('flickity-resize');
};

Flickity.createMethods.push('_createResizeClass');

var resize = Flickity.prototype.resize;
Flickity.prototype.resize = function() {
  this.element.classList.remove('flickity-resize');
  resize.call( this );
  this.element.classList.add('flickity-resize');
};

function mqlWatch(mediaQuery, layoutChangedCallback) {
  var mql = window.matchMedia(mediaQuery);
  mql.addListener(function (e) { return layoutChangedCallback(e.matches); });
  layoutChangedCallback(mql.matches);
}


