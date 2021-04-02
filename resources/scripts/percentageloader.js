/*
jquery.percentageloader.js 
 
Copyright (c) 2012, Better2Web
All rights reserved.

This jQuery plugin is licensed under the Simplified BSD License. Please
see the file license.txt that was included with the plugin bundle.

*/

/*global jQuery */

;(function ($) {
  /* Strict mode for this plugin */
  'use strict'
  /*jslint browser: true */

  /* Our spiral gradient data */
  var imgdata =
      'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAIAAABMXPacAAAACXBIWXMAAAsTAAALEwEAmpwYAAAGtGlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNi4wLWMwMDYgNzkuMTY0NzUzLCAyMDIxLzAyLzE1LTExOjUyOjEzICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1sbnM6ZGM9Imh0dHA6Ly9wdXJsLm9yZy9kYy9lbGVtZW50cy8xLjEvIiB4bWxuczpwaG90b3Nob3A9Imh0dHA6Ly9ucy5hZG9iZS5jb20vcGhvdG9zaG9wLzEuMC8iIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBNYWNpbnRvc2giIHhtcDpDcmVhdGVEYXRlPSIyMDIxLTAzLTI5VDE4OjU3OjA4LTA3OjAwIiB4bXA6TW9kaWZ5RGF0ZT0iMjAyMS0wMy0yOVQxOToxMDoxNi0wNzowMCIgeG1wOk1ldGFkYXRhRGF0ZT0iMjAyMS0wMy0yOVQxOToxMDoxNi0wNzowMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo1Mjc0MWUxOC00YmVkLTgzNGUtOTUzYS02MzEyYWIwMTFlM2QiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MDM4RjdFNzU5MzAyMTFFMUFFQTdENUVDNDUwOEI2RUYiIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDowMzhGN0U3NTkzMDIxMUUxQUVBN0Q1RUM0NTA4QjZFRiIgZGM6Zm9ybWF0PSJpbWFnZS9wbmciIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjAzOEY3RTcyOTMwMjExRTFBRUE3RDVFQzQ1MDhCNkVGIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjAzOEY3RTczOTMwMjExRTFBRUE3RDVFQzQ1MDhCNkVGIi8+IDx4bXBNTTpIaXN0b3J5PiA8cmRmOlNlcT4gPHJkZjpsaSBzdEV2dDphY3Rpb249InNhdmVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOmU0MTFlMDNlLTc4YjEtNDc0NS05NzA3LWM5NDI3YzBhNmZiNCIgc3RFdnQ6d2hlbj0iMjAyMS0wMy0yOVQxODo1OToyMi0wNzowMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIDIyLjMgKFdpbmRvd3MpIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJzYXZlZCIgc3RFdnQ6aW5zdGFuY2VJRD0ieG1wLmlpZDo1Mjc0MWUxOC00YmVkLTgzNGUtOTUzYS02MzEyYWIwMTFlM2QiIHN0RXZ0OndoZW49IjIwMjEtMDMtMjlUMTk6MTA6MTYtMDc6MDAiIHN0RXZ0OnNvZnR3YXJlQWdlbnQ9IkFkb2JlIFBob3Rvc2hvcCAyMi4zIChXaW5kb3dzKSIgc3RFdnQ6Y2hhbmdlZD0iLyIvPiA8L3JkZjpTZXE+IDwveG1wTU06SGlzdG9yeT4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz6XQ+2RAAAYNUlEQVR4nL2dS5LkOHKG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',
    // var imgdata =
    //     'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAIAAAACACAIAAABMXPacAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBNYWNpbnRvc2giIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MDM4RjdFNzQ5MzAyMTFFMUFFQTdENUVDNDUwOEI2RUYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MDM4RjdFNzU5MzAyMTFFMUFFQTdENUVDNDUwOEI2RUYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowMzhGN0U3MjkzMDIxMUUxQUVBN0Q1RUM0NTA4QjZFRiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowMzhGN0U3MzkzMDIxMUUxQUVBN0Q1RUM0NTA4QjZFRiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pv4BSzoAAA+OSURBVHja1F1bkutK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',
    gradient = new Image()
  gradient.src = imgdata

  /** Percentage loader
     * @param	params	Specify options in {}. May be on of width, height, progress or value.
     *
     * @example $("#myloader-container).percentageLoader({
		    width : 256,  // width in pixels
		    height : 256, // height in pixels
		    progress: 0,  // initialise progress bar position, within the range [0..1]
		    value: '0kb'  // initialise text label to this value
		});
     */
  $.fn.percentageLoader = function (params) {
    var settings,
      canvas,
      percentageText,
      valueText,
      items,
      i,
      item,
      selectors,
      s,
      ctx,
      progress,
      value,
      cX,
      cY,
      lingrad,
      innerGrad,
      tubeGrad,
      innerRadius,
      innerBarRadius,
      outerBarRadius,
      radius,
      startAngle,
      endAngle,
      counterClockwise,
      completeAngle,
      setProgress,
      setValue,
      applyAngle,
      drawLoader,
      clipValue,
      outerDiv

    /* Specify default settings */
    settings = {
      width: 200,
      height: 200,
      progress: 5.0,
      value: 'OVERALL'
    }

    /* Override default settings with provided params, if any */
    if (params !== undefined) {
      $.extend(settings, params)
    } else {
      params = settings
    }

    outerDiv = document.createElement('div')
    outerDiv.style.width = settings.width + 'px'
    outerDiv.style.height = settings.height + 'px'
    outerDiv.style.position = 'relative'

    $(this).append(outerDiv)

    /* Create our canvas object */
    canvas = document.createElement('canvas')
    canvas.setAttribute('width', settings.width)
    canvas.setAttribute('height', settings.height)
    outerDiv.appendChild(canvas)

    /* Create div elements we'll use for text. Drawing text is
     * possible with canvas but it is tricky working with custom
     * fonts as it is hard to guarantee when they become available
     * with differences between browsers. DOM is a safer bet here */
    percentageText = document.createElement('div')
    percentageText.style.width = settings.width.toString() - 2 + 'px'
    percentageText.style.textAlign = 'center'
    percentageText.style.height = '50px'
    percentageText.style.left = 0
    percentageText.style.position = 'absolute'

    valueText = document.createElement('div')
    valueText.style.width = (settings.width - 2).toString() + 'px'
    valueText.style.textAlign = 'center'
    valueText.style.height = '0px'
    // valueText.style.overflow = 'hidden'
    valueText.style.left = 0
    valueText.style.position = 'absolute'

    /* Force text items to not allow selection */
    items = [valueText, percentageText]
    for (i = 0; i < items.length; i += 1) {
      item = items[i]
      selectors = [
        '-webkit-user-select',
        '-khtml-user-select',
        '-moz-user-select',
        '-o-user-select',
        'user-select'
      ]

      for (s = 0; s < selectors.length; s += 1) {
        $(item).css(selectors[s], 'none')
      }
    }

    /* Add the new dom elements to the containing div */
    outerDiv.appendChild(percentageText)
    outerDiv.appendChild(valueText)

    /* Get a reference to the context of our canvas object */
    ctx = canvas.getContext('2d')

    /* Set various initial values */

    /* Centre point */
    cX = canvas.width / 2 - 1
    cY = canvas.height / 2 - 1

    /* Create our linear gradient for the outer ring */
    lingrad = ctx.createLinearGradient(cX, 0, cX, canvas.height)
    lingrad.addColorStop(0, '#76d856')
    lingrad.addColorStop(0.5, '#d1f2d4')
    lingrad.addColorStop(1, '#ffffff')

    /* Create inner gradient for the outer ring */
    innerGrad = ctx.createLinearGradient(
      cX,
      cX * 0.133333,
      cX,
      canvas.height - cX * 0.133333
    )
    innerGrad.addColorStop(0, '#239500')
    // innerGrad.addColorStop(1, '#d9ebf7')

    /* Tube gradient (background, not the spiral gradient) */
    tubeGrad = ctx.createLinearGradient(cX, 0, cX, canvas.height)
    tubeGrad.addColorStop(0, '#eeeeee')
    // tubeGrad.addColorStop(1, '#aacee6')

    /* The inner circle is 2/3rds the size of the outer one */
    innerRadius = cX * 0.6666
    /* Outer radius is the same as the width / 2, same as the centre x
     * (but we leave a little room so the borders aren't truncated) */
    radius = cX - 2

    /* Calculate the radii of the inner tube */
    innerBarRadius = innerRadius + cX * 0.06
    outerBarRadius = radius - cX * 0.06

    /* Bottom left angle */
    startAngle = 2.1707963267949
    /* Bottom right angle */
    endAngle = 0.9707963267949 + Math.PI * 2.0

    /* Nicer to pass counterClockwise / clockwise into canvas functions
     * than true / false */
    counterClockwise = false

    /* Borders should be 1px */
    ctx.lineWidth = 1

    /**
     * Little helper method for transforming points on a given
     * angle and distance for code clarity
     */
    applyAngle = function (point, angle, distance) {
      return {
        x: point.x + Math.cos(angle) * distance,
        y: point.y + Math.sin(angle) * distance
      }
    }

    /**
     * render the widget in its entirety.
     */
    drawLoader = function () {
      /* Clear canvas entirely */
      ctx.clearRect(0, 0, canvas.width, canvas.height)

      /*** IMAGERY ***/

      /* draw outer circle */
      ctx.fillStyle = lingrad
      ctx.beginPath()
      ctx.strokeStyle = '#d1e2dc'
      ctx.arc(cX, cY, radius, 0, Math.PI * 2, counterClockwise)
      ctx.fill()
      ctx.stroke()

      /* draw inner circle */
      ctx.fillStyle = innerGrad
      ctx.beginPath()
      ctx.arc(cX, cY, innerRadius, 0, Math.PI * 2, counterClockwise)
      ctx.fill()
      ctx.strokeStyle = '#d1e2dc'
      ctx.stroke()

      ctx.beginPath()

      /**
       * Helper function - adds a path (without calls to beginPath or closePath)
       * to the context which describes the inner tube. We use this for drawing
       * the background of the inner tube (which is always at 100%) and the
       * progress meter itself, which may vary from 0-5.0 */
      function makeInnerTubePath(startAngle, endAngle) {
        var centrePoint,
          startPoint,
          controlAngle,
          capLength,
          c1,
          c2,
          point1,
          point2
        centrePoint = {
          x: cX,
          y: cY
        }

        startPoint = applyAngle(centrePoint, startAngle, innerBarRadius)

        ctx.moveTo(startPoint.x, startPoint.y)

        point1 = applyAngle(centrePoint, endAngle, innerBarRadius)
        point2 = applyAngle(centrePoint, endAngle, outerBarRadius)

        controlAngle = endAngle + 3.142 / 2.0
        /* Cap length - a fifth of the canvas size minus 4 pixels */
        capLength = cX * 0.2 - 4

        c1 = applyAngle(point1, controlAngle, capLength)
        c2 = applyAngle(point2, controlAngle, capLength)

        ctx.arc(cX, cY, innerBarRadius, startAngle, endAngle, false)
        ctx.bezierCurveTo(c1.x, c1.y, c2.x, c2.y, point2.x, point2.y)
        ctx.arc(cX, cY, outerBarRadius, endAngle, startAngle, true)

        point1 = applyAngle(centrePoint, startAngle, innerBarRadius)
        point2 = applyAngle(centrePoint, startAngle, outerBarRadius)

        controlAngle = startAngle - 3.142 / 2.0

        c1 = applyAngle(point2, controlAngle, capLength)
        c2 = applyAngle(point1, controlAngle, capLength)

        ctx.bezierCurveTo(c1.x, c1.y, c2.x, c2.y, point1.x, point1.y)
      }

      /* Background tube */
      ctx.beginPath()
      ctx.strokeStyle = '#d1e2dc'
      makeInnerTubePath(startAngle, endAngle)

      ctx.fillStyle = tubeGrad
      ctx.fill()
      ctx.stroke()

      /* Calculate angles for the the progress metre */
      completeAngle = startAngle + progress * 0.2 * (endAngle - startAngle)

      ctx.beginPath()
      makeInnerTubePath(startAngle, completeAngle)

      /* We're going to apply a clip so save the current state */
      ctx.save()
      /* Clip so we can apply the image gradient */
      ctx.clip()

      /* Draw the spiral gradient over the clipped area */
      ctx.drawImage(gradient, 0, 0, canvas.width, canvas.height)

      /* Undo the clip */
      ctx.restore()

      /* Draw the outline of the path */
      ctx.beginPath()
      makeInnerTubePath(startAngle, completeAngle)
      ctx.stroke()

      /*** TEXT ***/
      ;(function () {
        var fontSize, string, smallSize, heightRemaining
        /* Calculate the size of the font based on the canvas size */
        fontSize = cX / 2

        percentageText.style.top =
          (settings.height / 2 - fontSize / 2).toString() + 'px'
        percentageText.style.color = '#ffffff'
        percentageText.style.font = fontSize.toString() + 'px OpenSans-Bold'
        percentageText.style.textShadow = '0 1px 1px #FFFFFF'

        /* Calculate the text for the given percentage */
        string = Number(progress).toFixed(1)

        percentageText.innerHTML = string

        /* Calculate font and placement of small 'value' text */
        smallSize = cX / 8
        valueText.style.color = '#ffffff'
        valueText.style.font = smallSize.toString() + 'px OpenSans-Regular'
        valueText.style.height = smallSize.toString() + 'px'
        valueText.style.textShadow = 'None'

        /* Ugly vertical align calculations - fit into bottom ring.
         * The bottom ring occupes 1/6 of the diameter of the circle */
        heightRemaining = settings.height * 0.16666666 - smallSize
        valueText.style.top =
          (settings.height * 0.8333333 + heightRemaining / 4).toString() + 'px'
      })()
    }

    /**
     * Check the progress value and ensure it is within the correct bounds [0..1]
     */
    clipValue = function () {
      if (progress < 0) {
        progress = 0
      }

      if (progress > 5.0) {
        progress = 5.0
      }
    }

    /* Sets the current progress level of the loader
     *
     * @param value the progress value, from 0 to 1. Values outside this range
     * will be clipped
     */
    setProgress = function (value) {
      /* Clip values to the range [0..5] */
      progress = value
      clipValue()
      drawLoader()
    }

    this.setProgress = setProgress

    setValue = function (val) {
      value = val
      valueText.innerHTML = value
    }

    this.setValue = setValue
    this.setValue(settings.value)

    progress = settings.progress
    clipValue()

    /* Do an initial draw */
    drawLoader()

    return this
  }
})(jQuery)
