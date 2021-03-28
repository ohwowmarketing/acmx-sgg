jQuery(document).ready(function ($) {
  $('.hero-sb').on('click', '.hero-sb-bet-now', function (e) {
    e.preventDefault()
    const link = $(this).prop('href')
    const content = $(this).closest('div.hero-sb-content')
    const logo = content.find('.hero-sb-logo img').clone()
    const bonus = content.find('p').html()
    const sportsbook = content.find('h4').html()

    $('#bet-now .bet-now-sb-logo').html(logo)
    $('#bet-now h2').html(sportsbook)
    $('#bet-now p').html(bonus)
    $('#bet-now a').prop('href', link)
    UIkit.modal('#bet-now').show()
  })
})
