<?php 
$betting_states = get_field('betting_states', 'option');
$upcoming_states = get_field('upcoming_states', 'option');
?>
<div class="uk-card uk-card-default uk-card-body" data-card="states">
  <div class="uk-position-relative">
    <div uk-grid>
      <div class="uk-width-1-1@s uk-width-1-2@m">
        <div class="uk-width-1-1">
          <h1 class="uk-card-title">U.S. Betting States</h1>
        </div>
        <div uk-grid class="uk-grid-collapse state-top-border">
        <?php foreach ( $betting_states as $state ) : ?>
          <div class="uk-width-1-2 state-outer-border">
            <div uk-grid class="uk-grid-collapse state-borders">
              <div class="uk-width-auto">
                <div class="state-img">
                  <img src="<?php echo get_template_directory_uri(); ?>/resources/images/states/<?php echo $state; ?>.svg" alt="<?php echo api_state_from_code( $state ); ?>" />
                </div>
              </div>
              <div class="uk-width-expand">
                <div class="state-name"><?php echo api_state_from_code( $state ); ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
      <div class="uk-width-1-1@s uk-width-1-2@m">
        <div class="uk-width-1-1">
            <h1 class="uk-card-title">Upcoming Betting States</h1>
          </div>
          <div uk-grid class="uk-grid-collapse state-top-border">
          <?php foreach ( $upcoming_states as $state ) : ?>
            <div class="uk-width-1-2 state-outer-border">
              <div uk-grid class="uk-grid-collapse state-borders">
                <div class="uk-width-auto">
                  <div class="state-img">
                    <img src="<?php echo get_template_directory_uri(); ?>/resources/images/states/<?php echo $state; ?>.svg" alt="<?php echo api_state_from_code( $state ); ?>" />
                  </div>
                </div>
                <div class="uk-width-expand">
                  <div class="state-name"><?php echo api_state_from_code( $state ); ?></div>
                </div>
              </div>
            </div>
        <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>