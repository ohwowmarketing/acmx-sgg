jQuery(document).ready(function($) {
  jQuery('#market_id').change(function() {
    this.form.submit();
  });
});