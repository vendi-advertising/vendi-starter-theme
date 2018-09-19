<?php

//Load CSS and JavaScript
add_action(
            'wp_enqueue_scripts',
            function()
            {
                $media_dir = untrailingslashit( apply_filters('vendi/asset_loader/get_media_dir_abs', get_template_directory()) );
                $media_url = untrailingslashit( apply_filters('vendi/asset_loader/get_media_url_abs', get_template_directory_uri()) );

                $css_files = glob( $media_dir . '/css/[0-9][0-9][0-9]-*.css' );

                if(false !== $css_files && count($css_files) > 0)
                {
                    //Load each CSS file that starts with three digits followed by a dash in numerical order
                    foreach( $css_files as $t )
                    {
                        wp_enqueue_style(
                                            basename( $t, '.css' ) . '-p-style',
                                            $media_url . '/css/' . basename( $t ),
                                            null,
                                            filemtime( $media_dir . '/css/' . basename( $t ) ),
                                            'screen'
                                        );
                    }
                }

                $js_files = glob( $media_dir . '/js/[0-9][0-9][0-9]-*.js' );
                if(false !== $js_files && count($js_files) > 0)
                {
                    //Load each JS file that starts with three digits followed by a dash in numerical order
                    foreach( $js_files as $t )
                    {
                        wp_enqueue_script(
                                            basename( $t, '.js' ) . '-p-style',
                                            $media_url . '/js/' . basename( $t ),
                                            null,
                                            filemtime( $media_dir . '/js/' . basename( $t ) ),
                                            true
                                        );
                    }
                }
            }
        );
