<div class="ui-comments">
    <form action="" class="ui-comments-msgboard uk-animation-toggle" tabindex="0">
    <?php 
        // Check if user is logged-in & subscriber
        $loggedIn = is_user_logged_in();
        $user = wp_get_current_user();
        $allowedRole = ['subscriber'];
        if ( $loggedIn && array_intersect( $allowedRole, $user->roles ) ) : ?>
        <a class="ui-cm-avatar">
            <img class="uk-border-circle" width="42" height="42" src="<?php echo get_avatar_url(wp_get_current_user()->ID); ?>">
        </a>
        <div class="ui-cm-widget-controls-alt uk-hidden@s" hidden>
            <a href="#" class="ui-cm-widget-gif"></a>
            <a href="#" class="ui-cm-widget-emoticon uk-link-reset"><span uk-icon="icon: happy; ratio: 1.3;"></span></a>
        </div>
        <input type="text" name="ui-cm-field" class="ui-cm-field" placeholder="Write a comment" autocomplete="off" id="reply" required>
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
        <a href="#AuthPanelLogin" class="ui-cm-field" uk-toggle="animation: uk-animation-fade">Login or register to post a reply</a>
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

    <div class="ui-comments-body" id="replies-body">
        <div id="replies">
            
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

            <div class="ui-comment -reply" hidden>
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

        </div>
    </div>

    <?php // Login Widget ?>
    <div id="AuthPanelLogin" class="uk-position-cover uk-overlay uk-overlay-default uk-flex uk-flex-center uk-overflow-auto" hidden>
        <div class="uk-width-large uk-panel">
            <ul class="uk-subnav uk-subnav-pill" uk-switcher>
                <li class="uk-active"><a href="#">Login</a></li>
                <li><a href="#">Register</a></li>
            </ul>

            <ul class="uk-switcher uk-margin">
                <li>
                    <form id="AuthPanelLoginUser" class="uk-form-stacked uk-light" action="" method="post">
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
                </li>
                <li>
                    <form id="AuthPanelRegisterUser" class="uk-form-stacked uk-light" action="" method="post">
                        <div class="status"></div>

                        <div class="uk-button-group">
                            <div class="" uk-form-custom="target: true">
                                <input type="file">
                                <input class="uk-input uk-form-width-medium" type="text" placeholder="Upload Your Avatar" disabled>
                            </div>
                            <button class="uk-button uk-button-default"><span uk-icon="icon: image"></span></button>
                        </div>
                        <div class="uk-text-meta uk-margin-small-top uk-margin-small-bottom">Size Restrictions: Please make sure the image size is equal to or larger than 150 by 150 pixels.</div>

                        <div class="uk-inline">
                            <input id="username" class="uk-input" type="text" placeholder="Username *" name="username" required>
                            <input id="firstname" class="uk-input" type="text" placeholder="First Name *" name="firstname" required>
                            <input id="lastname" class="uk-input" type="text" placeholder="Last Name *" name="lastname" required>
                            <input id="email" class="uk-input" type="text" placeholder="Email *" name="email" required>
                            <input id="password1" class="uk-input" type="password" placeholder="Password *" name="password1">
                            <input id="password2" class="uk-input" type="password" placeholder="Confirm Password *" name="password2">
                        </div>
                        <div class="uk-margin">
                            <button id="submit" class="uk-button uk-button-primary uk-button-small" type="submit" name="submit">Register</button>
                            <div class="uk-text-meta uk-margin-small-top">Fields with ( * ) are required</div>
                        </div>
                        <?php wp_nonce_field( 'ajax-login-nonce', 'security' ); ?>
                    </form>                    
                </li>
        </div>
        <a href="#AuthPanelLogin" class="uk-position-small uk-position-top-rigt uk-light --close-overlay" uk-toggle="animation: uk-animation-fade">&times;</a>

    </div>
</div>