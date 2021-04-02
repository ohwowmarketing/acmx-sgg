<?php 
$betting_states = get_field('betting_states', 'option');
$upcoming_states = get_field('upcoming_states', 'option');
?>
<div class="uk-card uk-card-default uk-card-body" data-card="states">
  <div style="position: relative">
    <div uk-grid>
      <div class="uk-width-1-1@s uk-width-1-2@m">
        <h1 class="uk-card-title">U.S. Betting States</h1>
        <div uk-grid>
        <?php foreach ( $betting_states as $state ) : ?>
          <div class="uk-width-1-2">
            <div class="state-display">
              <img src="<?php echo get_template_directory_uri(); ?>/resources/images/states/<?php echo $state; ?>.svg" />
              <p><?php echo get_state_from_code( $state ); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
      <div class="uk-width-1-1@s  uk-width-1-2@m">
        <h1 class="uk-card-title">Upcoming Betting States</h1>
        <div uk-grid>
        <?php foreach ( $upcoming_states as $state ) : ?>
          <div class="uk-width-1-2">
            <div class="state-display">
              <img src="<?php echo get_template_directory_uri(); ?>/resources/images/states/<?php echo $state; ?>.svg" />
              <p><?php echo get_state_from_code( $state ); ?></p>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>