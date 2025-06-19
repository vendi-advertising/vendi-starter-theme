<?php

add_action(
    'login_enqueue_scripts',
    static function () {
        if ($backgroundImage = get_field('login_background_image', 'option')) {
            [$loginBackgroundPositionX, $loginBackgroundPositionY] = sanitize_focal_point(get_post_meta($backgroundImage['ID'], 'focal_point', true));
            $loginBackgroundPositionX *= 100;
            $loginBackgroundPositionY *= 100;
        } else {
            $backgroundImage          = null;
            $loginBackgroundPositionX = 50;
            $loginBackgroundPositionY = 50;
        }

        if ( ! ($loginLogo = get_field('login_logo', 'option'))) {
            $loginLogo = null;
        }

        ?>
        <script>
            /*global window */

            (function (w) {

                    'use strict'; //Force strict mode

                    const
                        document = w.document,

                        load = () => {
                            const d = document.createElement('div');
                            d.classList.add('vendi-login-background');
                            document.body.append(d);

                            const a = document.querySelector('#login h1 a');
                            if (!a) {
                                return;
                            }

                            a.removeAttribute('href');
                        },

                        init = () => {
                            if (['complete', 'interactive'].includes(document.readyState)) {
                                //If the DOM is already set then invoke our load function
                                load();
                            } else {
                                //Otherwise, wait for the ready event
                                document.addEventListener('DOMContentLoaded', load);
                            }
                        }
                    ;

                    //Kick everything off
                    init();
                }
            )(window);

        </script>
        <style>
            body.login {
                display: grid;
                grid-template-columns: 1fr max-content;
                background-color: rgba(10, 75, 120, 0.13);

                .vendi-login-background {
                    grid-column: 1;
                    grid-row: 1;
                    background-size: cover;
                    background-repeat: no-repeat;
                }

            <?php if($backgroundImage): ?>

                .vendi-login-background {
                    grid-column: 1;
                    grid-row: 1;
                    background-image: url('<?php echo esc_url($backgroundImage['url']) ?>');
                    background-position: <?php echo $loginBackgroundPositionX; ?>% <?php echo $loginBackgroundPositionY; ?>%;
                }

            <?php endif; ?>

                h1 a {
                    background-size: contain;
                    background-position: center;
                    background-repeat: no-repeat;
                    width: 100%;
                    height: 100px;
                }

            <?php if($loginLogo): ?>

                h1 a {
                    background-image: url('<?php echo esc_url($loginLogo['url']) ?>');
                }

            <?php endif; ?>

                #login {
                    width: unset;
                    min-width: 400px;
                    max-width: 500px;
                    margin-inline: auto;
                    padding: 15px;
                    margin-block: 5em;
                }

                @media screen and (max-width: 750px) {
                    & {
                        grid-template-columns: 1fr;

                        .vendi-login-background {
                            display: none;
                        }
                    }
                }
            }
        </style>
    <?php },
);
