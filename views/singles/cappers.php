<main id="main" class="main" role="main">
    <div class="uk-container uk-container-xlarge">
        <div class="uk-grid-small" uk-grid>
            <div class="uk-width-expand@l">

                <div class="uk-card uk-card-body" data-card="cappers-stats">
                    <div class="uk-grid-medium uk-child-width-auto uk-flex-middle" uk-grid>
                        <div>
                            <a class="avatar">
                                <img class="uk-border-rounded" width="120" height="120" src="https://i.pravatar.cc/120?img=21" alt="">
                            </a>
                        </div>
                        <div>
                            <small class="author">omelettedufromage</small>
                            <h1>Buccaneers <span class="odds">-7.0</span> <span class="uk-display-block opponent">vs Eagles</span></h1>
                            <div class="uk-child-width-auto" uk-grid>
                                <div>
                                    <div class="uk-panel">
                                        Capper’s Record
                                        <span class="uk-display-block value">8–2</span>
                                    </div>
                                </div>
                                <div>
                                    <div class="uk-panel">
                                        Win PCT
                                        <span class="uk-display-block value">80%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="--modal-action">
                        <a href="#standings"><span class="uk-visible@s">Cappers’ Standings</span></a>
                    </div>
                </div>
                
                <div class="uk-card uk-card-default" data-card="cappers-analysis">
                    <div class="uk-card-header">
                        <h4>Omelettedufromage</h4>
                        <time>October 12, 2021</time>
                    </div>
                    <div class="uk-card-body">
                        <p>Nunc justo velit, bibendum luctus risus nec, interdum molestie tellus. Quisque aliquam lorem arcu. Nulla sit amet tortor non magna congue dictum quis commodo nibh. Pellentesque volutpat ligula ex, eget tempus libero porttitor eu. Etiam mattis est vel iaculis sodales. Nullam ornare sapien quis urna sodales efficitur. Phasellus eu erat faucibus, vehicula sem vel, hendrerit metus.</p>
                        <p>Etiam rhoncus, metus sit amet porttitor suscipit, mauris libero pretium tellus, nec efficitur neque diam a elit. Mauris orci odio, blandit vitae bibendum a, tristique a nunc. Suspendisse ac mi ac velit maximus vestibulum. Aenean facilisis sem vitae ultrices suscipit. Nullam vestibulum, erat vel iaculis venenatis, justo ipsum lobortis lectus, nec sagittis libero enim blandit mi.</p>
                    </div>
                </div>

                <!-- Chat Plugin Here -->
                <div class="uk-card uk-card-default uk-card-body" data-card="cappers-chat">
                    <?php get_template_part( widget . 'chat' ); ?>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-large@l">
                <?php get_template_part( widget . 'news' ); ?>
            </div>
        </div>
    </div>
</main>