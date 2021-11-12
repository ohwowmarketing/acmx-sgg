<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">

                <div class="uk-card uk-card-default uk-card-body" data-card="cappers-corner">
                    <div class="uk-card-title">
                        <h3>Cappers Corner</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis.</p>
                    </div>

                    <div uk-grid class="uk-grid-small uk-grid-divider --cappers-corner-wrapper">
                        <div class="uk-width-auto@xl --cappers-profile-list">

                            <div class="uk-grid-title">
                                <h4>Cappers Corner Picks</h4>
                                <p>Top picks of the day</p>
                            </div>

                            <!-- Dynamic Cappers Here -->
                            <ul class="--cappers-wrapper" uk-accordion="active: 0; content: > .uk-card .uk-card-body; toggle: > .uk-card .uk-card-header .--cappers-action">
                                <?php for ( $n=0;$n<5;$n++ ) : ?>
                                <li class="--cappers-profile">
                                    <div class="uk-card uk-card-default uk-card-small">
                                        <div class="uk-card-header">
                                            <div class="uk-grid-small uk-flex-top uk-flex-between" uk-grid>
                                                <div class="uk-width-auto">
                                                    <img class="uk-border-circle" width="40" height="40" src="https://i.pravatar.cc/300?img=<?=$n?>">
                                                </div>
                                                <div class="uk-width-expand">
                                                    <small>omelettedufromage</small>
                                                    <h4>Buccaneers [-7.0] <span>vs Eagles</span></h4>
                                                </div>
                                                <div class="uk-width-auto --cappers-action">
                                                    <a href="#"></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uk-card-body">
                                            <div class="uk-grid-collapse uk-flex-between uk-grid-match" uk-grid>
                                                <div class="uk-width-expand --profile-action">
                                                    <a href="<?php echo __(site_url( '/cappers-corner/buccaneers' )); ?>">Read capper’s analysis</a>
                                                </div>
                                                <div class="--modal-action">
                                                    <a href="#standings"><span class="uk-visible@s">Cappers’ Standings</span></a>
                                                </div>
                                            </div>
                                            <div class="uk-grid-collapse uk-flex-middle uk-flex-between" uk-grid>
                                                <div class="uk-width-auto --cappers-stats">
                                                    Cappers' Record: <strong>8 - 2</strong>
                                                </div>
                                                <div class="uk-width-auto --cappers-stats">
                                                    Win PCT: <strong>80%</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php endfor; ?>
                            </ul>

                        </div>
                        <div class="uk-width-expand@xl --cappers-chat-plugin">

                            <div class="uk-grid-title">
                                <h4>Cappers Corner Chat</h4>
                                <p>Join the Discussion</p>
                            </div>

                            <!-- Chat Plugin Here -->
                            <?php get_template_part( widget . 'chat' ); ?>
                        </div>
                    </div>
                </div>

            </div>


            <div class="uk-width-1-1 uk-width-large@l">
                <?php 
                    get_template_part( widget . 'news' );
                    get_template_part( widget . 'instagram' );
                ?>
            </div>
        </div>
    </div>
</main>