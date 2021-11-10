;(function ($) {
  var $, $uri, $plugin, $vendor, $modal, theme

  //* Create Root Path
  $uri = window.location.origin
  $plugin = $uri + '/wp-content/themes/acmx-sgg/resources/scripts/inc/'
  $vendor = $uri + '/wp-content/themes/acmx-sgg/vendor/'

  //* Convert jQuery $
  // $ = jQuery;

  theme = {
    common: {
      init: function () {
        jQuery.getScript($plugin + 'common.js')
      }
    },

    home: {
      init: function () {
        jQuery.getScript($plugin + 'home.js')
      }
    },

    // NFL/NBA/MLB Odds
    odds_betting_lines: {
      init: function () {
        jQuery.getScript($plugin + 'betting-odds.js')
      }
    },
    
    // Cappers Corner
    // cappers_corner: {
    //   init: function () {
    //     jQuery.getScript($plugin + 'cappers.js')
    //   }
    // }
  }

  var UTIL = {
    fire: function (func, funcname, args) {
      var fire
      var namespace = theme
      funcname = funcname === undefined ? 'init' : funcname
      fire = func !== ''
      fire = fire && namespace[func]
      fire = fire && typeof namespace[func][funcname] === 'function'

      if (fire) {
        namespace[func][funcname](args)
      }
    },
    loadEvents: function () {
      //* Fire common init JS
      UTIL.fire('common')

      //* Fire page-specific init JS, and then finalize JS
      jQuery.each(
        document.body.className.replace(/-/g, '_').split(/\s+/),
        function (i, classnm) {
          UTIL.fire(classnm)
          UTIL.fire(classnm, 'finalize')
        }
      )

      //* Fire common finalize JS
      UTIL.fire('common', 'finalize')
    }
  }

  // Load Events
  jQuery(document).ready(UTIL.loadEvents)
})(jQuery)

//*
// Add event listener offline to detect network loss.
window.addEventListener('offline', function (e) {
  showPopForOfflineConnection()
})

// Add event listener online to detect network recovery.
window.addEventListener('online', function (e) {
  hidePopAfterOnlineInternetConnection()
})

function hidePopAfterOnlineInternetConnection() {
  // Set your alert here...
}

function showPopForOfflineConnection() {
  alert("Ooppss! There's something wrong about your internet. Please check!")
}
