<div class="ui-comments">
    <form action="" class="ui-comments-msgboard uk-animation-toggle" tabindex="0">
    <?php 
        // Check if user is logged-in & subscriber
        $loggedIn = is_user_logged_in();
        $user = wp_get_current_user();
        $allowedRole = ['subscriber'];
        if ( $loggedIn && array_intersect( $allowedRole, $user->roles ) ) : ?>
        <a class="ui-cm-avatar">
            <img class="uk-border-circle" width="42" height="42" src="https://i.pravatar.cc/42?img=18">
        </a>
        <div class="ui-cm-widget-controls-alt uk-hidden@s" hidden>
            <a href="#" class="ui-cm-widget-gif"></a>
            <a href="#" class="ui-cm-widget-emoticon uk-link-reset"><span uk-icon="icon: happy; ratio: 1.3;"></span></a>
        </div>
        <input type="text" name="ui-cm-field" class="ui-cm-field" placeholder="Write a comment" autocomplete="off">
        <div class="ui-cm-widget-controls">
            <div class="uk-flex uk-flex-middle uk-visible@s">
                <a href="#" class="ui-cm-widget-gif"></a>
                <a href="#" class="ui-cm-widget-emoticon uk-link-reset"><span uk-icon="icon: happy; ratio: 1.5;"></span></a>
            </div>
            <div class="uk-hidden@s">
                <a href="#emojis" uk-icon="icon: more" uk-toggle="target: .ui-cm-widget-controls-alt; animation: uk-animation-slide-bottom-small"></a>
            </div>
            <button type="submit" name="ui-msg-submit" class="ui-msg-submit"></button>
        </div>
        <?php else : ?>
        <a href="#AuthPanelLogin" class="ui-cm-field" uk-toggle="animation: uk-animation-fade">Click here to login</a>
        <?php endif; ?>
    </form>

    <header class="ui-comments-header uk-light">
        <h1 class="ui-ch-channel">Locks of the Week</h1>
        <div class="ui-ch-action">

            <div class="ui-ch-moreaction-alt uk-hidden@s">
                <a href="#more" uk-icon="icon: more-vertical"></a>
                <div uk-dropdown="mode: click; offset: 15; pos: bottom-right">
                    <ul class="uk-nav uk-dropdown-nav">
                        <?php if ( $loggedIn ) : ?>
                        <li class="uk-nav-header">Howdy, <?php echo $user->data->user_nicename; ?></li>
                        <li> <a href="#">Edit My Profile</a> </li>
                        <li> <?php echo wp_loginout( site_url('cappers-corner') ); ?> </li>
                        <?php else : ?>
                        <li> <a href="#AuthPanelLogin" uk-toggle="animation: uk-animation-fade">Log In</a> </li>
                        <li class="uk-nav-header">Don't have an account?</li>
                        <li> <a href="#">Click here to Register</a> </li>
                        <?php endif; ?>
                        <li class="uk-nav-header">Select Channels</li>
                        <li><a href="#">Locks Of The Week</a></li>
                        <li><a href="#">Sweat It Out Together</a></li>
                        <li><a href="#">Futures</a></li>
                        <li><a href="#">Giveaways</a></li>
                    </ul>
                </div>
            </div>
            <div class="ui-ch-moreaction uk-visible@s">
                <div class="ui-ch-channels">
                    <button class="uk-button uk-button-default uk-button-small" type="button">Select Channel <span uk-icon="icon: triangle-down; ratio: 0.8"></span></button>
                    <div uk-dropdown="mode: click; offset: 13; pos: bottom-center">
                        <ul class="uk-nav uk-dropdown-nav">
                            <li><a href="#">Locks Of The Week</a></li>
                            <li><a href="#">Sweat It Out Together</a></li>
                            <li><a href="#">Futures</a></li>
                            <li><a href="#">Giveaways</a></li>
                        </ul>
                    </div>
                </div>
                <div class="ui-ch-moreaction">
                    <a href="#more" class="" uk-icon="icon: user" uk-tooltip="title: My Account; pos: bottom"></a>
                    <div uk-dropdown="mode: click; offset: 17; pos: bottom-right">
                        <ul class="uk-nav uk-dropdown-nav">
                            <?php if ( $loggedIn ) : ?>
                            <li class="uk-nav-header">Howdy, <?php echo $user->data->user_nicename; ?></li>
                            <li> <a href="#">Edit My Profile</a> </li>
                            <li> <?php echo wp_loginout( site_url('cappers-corner') ); ?> </li>
                            <?php else : ?>
                            <li> <a href="#AuthPanelLogin" uk-toggle="animation: uk-animation-fade">Log In</a> </li>
                            <li class="uk-nav-header">Don't have an account?</li>
                            <li> <a href="#">Click here to Register</a> </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="ui-comments-body">

        <div class="ui-comment">
            <a class="avatar">
                <img class="uk-border-circle" width="42" height="42" src="https://i.pravatar.cc/42?img=22">
            </a>
            <div class="content">
                <a class="author">‘FenrirCrow</a>
                <div class="metadata">
                    <time class="date">Oct 22 8:45 AM</time>
                </div>
                <div class="message">
                    Hey guys, I hope this example comment is helping you read this documentation.
                </div>
            </div>
        </div>

        <div class="ui-comment -reply">
            <a class="avatar">
                <img class="uk-border-circle" width="42" height="42" src="https://i.pravatar.cc/42?img=15">
            </a>
            <div class="content">
                <a class="author">Tens</a>
                <div class="metadata">
                    <time class="date">Oct 22 3:45 PM</time>
                </div>
                <div class="message">
                    I wish buddy, wouldve gladly lend it to you
                </div>
                <div class="reply uk-text-truncate">
                    <strong>@‘FenrirCrow</strong> Hey guys, I hope this example comment is helping you read this documentation. Lorem ipsum dolor sit amet consectetur adipisicing elit. Nulla blanditiis obcaecati, odit, delectus, modi reiciendis nobis sit maiores et amet aut nesciunt id magni impedit error fugit eius eum consequatur?
                </div>
            </div>
        </div>
        
        <?php for ($n=0;$n<=3;$n++) : ?>
        <div class="ui-comment">
            <a class="avatar">
                <img class="uk-border-circle" width="42" height="42" src="https://i.pravatar.cc/42?img=18">
            </a>
            <div class="content">
                <a class="author">DiRTeeAgent</a>
                <div class="metadata">
                    <time class="date">Oct 23 8:45 AM</time>
                </div>
                <div class="message">
                    If I didn't sell back to them and price went up would it be easy to sell. Sorry for my ignorance. I really know nothing about nfts but anything to make a profit!
                </div>
            </div>
        </div>
        <?php endfor; ?>

    </div>

    <?php // Login Widget ?>
    <div id="AuthPanelLogin" class="uk-position-cover uk-overlay uk-overlay-default uk-height-1-1" hidden>
        
        <div class="uk-position-center uk-panel">
            <form id="AuthPanelLoginUser" class="uk-form-stacked" action="" method="post">
                <div class="status"></div>

                <div class="uk-inline">
                    <input id="username" class="uk-input" type="text" placeholder="Username" name="username">
                    <input id="password" class="uk-input" type="password" placeholder="Password" name="password">  
                </div>
                <div class="uk-margin">
                    <button id="submit" class="uk-button uk-button-primary uk-button-small" type="submit" name="submit">Login</button>
                </div>
                <hr class="uk-divider-small">
                <div class="uk-inline uk-light">
                    <a href="<?php echo esc_url( wp_lostpassword_url( get_permalink() ) ); ?>" alt="<?php esc_attr_e( 'Forgot Password', 'acmx-sgg' ); ?>">
                        <?php esc_html_e( 'Forgot Password?', 'acmx-sgg' ); ?>
                    </a>                    
                </div>
                <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
            </form>
        </div>
        <a href="#AuthPanel" class="uk-position-small uk-position-top-right uk-light" uk-toggle></a>

    </div>
</div>