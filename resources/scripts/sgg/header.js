jQuery(document).ready(function ($) {
  const star = `<img src="${SGGAPI.directory}/resources/images/ui/star.svg" class="rating" />`
  const filled = `<div class="rating-circle">${star}</div>`
  const empty = `<div class="rating-circle empty">${star}</div>`
  const half = `<div class="rating-circle half">${star}</div>`
  function star_rating(val, max) {
    const rating = parseFloat(val)
    const maxRating = parseInt(max)
    let result = '<div class="rating-container">'
    for (let i = 0; i < maxRating; i++) {
      if (rating >= i + 1) {
        result += filled
      } else if (rating > i && rating < i + 1) {
        result += half
      } else {
        result += empty
      }
    }
    result += `<div class="rating-numeric">${val}/${max.toFixed(1)}</div></div>`
    return result
  }

  const percentageRating = $('#top-loader').percentageLoader()
  $('.hero-sb-info').hide()
  $('body').on('change', '.state-select', function () {
    const state = $('option:selected', this)
    if (state.data('bonus') === '' && state.data('link') !== '') {
      window.location.href = state.data('link')
      return
    }

    $('#bet-now .bonus').html(state.data('bonus'))
    $('#bet-now h2 span').html(`: ${state.text()}`)
    $('#bet-now a.continue').prop('href', state.data('link'))
    $('#bet-now .select-container').hide()
    $('#bet-now .outside').hide()
    $('#bet-now a.continue').show()
  })

  $('.hero-sb').on('click', '.close-info', function () {
    console.log('Close has been clicked')
    $('.hero-sb-info').hide()
  })

  $('.hero-sb').on('click', '.sb-more-info', function (e) {
    e.preventDefault()
    const slug = $(this, '.sb-more-info').data('sbid')
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'sportsbook_info',
        nonce: SGGAPI.nonce,
        slug: slug
      },
      function (data) {
        const sb = JSON.parse(data)
        // $('.sb-info a.hero-sb-bet-now').data('sbid', sb.slug)
        $('.sb-info .hero-sb-bet-now').data('sbid', sb.slug)
        $('.sb-info h2 span').html(sb.title)
        $('.sb-info-terms p').html(sb.bonus)
        $('.sb-info-description').html(sb.description)
        $('.sb-info table tbody').html('')
        if (sb.ratings) {
          sb.ratings.map((rating) => {
            const stars = star_rating(rating.rating, 5)
            $('.sb-info table tbody').append(
              `<tr><th>${rating.label}</th><td>${stars}</td></tr>`
            )
          })
        }
        percentageRating.setProgress(sb.rating)
        // $('.hero-sb-info').css('visibility', 'visible')
        // $('.hero-sb .hero-sb-info').css('display')
        $('.hero-sb-info').show()
      }
    )
  })

  $('.hero-sb').on('click', '.hero-sb-bet-now', function (e) {
    e.preventDefault()
    $('.hero-sb-info').hide()
    const slug = $(this, '.hero-sb-bet-now').data('sbid')
    $.post(
      SGGAPI.ajax_url,
      {
        action: 'sportsbook_modal',
        nonce: SGGAPI.nonce,
        slug: slug
      },
      function (data) {
        const sb = JSON.parse(data)

        if (sb.in_state && !sb.state_specific) {
          window.location.href = sb.link
          return
        }

        $('#bet-now .bet-now-sb-logo').html(
          `<img src="${sb.logo}" alt="${sb.title}" />`
        )
        $('#bet-now h2').html(`${sb.title}<span></span>`)
        $('#bet-now .bonus').html(sb.bonus)
        $('#bet-now a.continue').prop('href', sb.link)
        $('#bet-now select.state-select').html('')
        sb.states.map((state) => {
          $('#bet-now select.state-select').append(
            `<option value="${state.abbr}" data-bonus="${state.bonus}" data-link="${state.link}">${state.full}</option>`
          )
        })

        if (!sb.in_state) {
          $('#bet-now .outside').show()
          $('#bet-now .select-container').show()
          $('#bet-now a.continue').hide()
        } else {
          $('#bet-now .outside').hide()
          $('#bet-now .select-container').hide()
          $('#bet-now a.continue').show()
        }

        UIkit.modal('#bet-now').show()
      }
    )
    // const link = $(this).prop('href')
    // const content = $(this).closest('div.hero-sb-content')
    // const logo = content.find('.hero-sb-logo img').clone()
    // const bonus = content.find('p').html()

    // $('#bet-now .bet-now-sb-logo').html(logo)
    // $('#bet-now h2').html(sportsbook)
    // $('#bet-now p').html(bonus)
    // $('#bet-now a').prop('href', link)
  })
})
