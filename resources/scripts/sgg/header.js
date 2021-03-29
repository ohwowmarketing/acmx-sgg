jQuery(document).ready(function ($) {
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

  $('.hero-sb').on('click', '.hero-sb-bet-now', function (e) {
    e.preventDefault()
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
