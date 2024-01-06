<footer class="site-footer">
    <div class="footer-wrap region">
        <div class="logo-and-address-and-social">
            <div class="logo">
                <a href="<?php esc_attr_e(home_url()); ?>">
                    <img alt="" src="<?php esc_attr_e(get_template_directory_uri()); ?>/images/starter-content/bird-logo.svg"/>
                </a>
            </div>
            <div class="address">
                <p>
                    <strong>Client Name, Inc.</strong><br/>
                    1234 Main Street<br/>
                    City, State 12345<br/>
                    Earth, Sol System, Milky Way Galaxy<br/>
                    The Known Universe
                </p>
            </div>
            <div class="footer-social">
                <ul class="list-as-nav">
                    <li class="facebook">
                        <a aria-label="Facebook" target="_blank" href="#"><?php vendi_get_svg('images/social-icons/facebook.svg'); ?></a>
                    </li>
                    <li class="instagram">
                        <a aria-label="Instagram" target="_blank" href="#"><?php vendi_get_svg('images/social-icons/instagram.svg'); ?></a>
                    </li>
                    <li class="twitter">
                        <a aria-label="Twitter" target="_blank" href="#"><?php vendi_get_svg('images/social-icons/twitter.svg'); ?></a>
                    </li>
                    <li class="linkedin">
                        <a aria-label="LinkedIn" target="_blank" href="#"><?php vendi_get_svg('images/social-icons/linkedin.svg'); ?></a>
                    </li>
                    <li class="youtube">
                        <a aria-label="YouTube" target="_blank" href="#"><?php vendi_get_svg('images/social-icons/youtube.svg'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
        <nav class="footer-nav">
            <?php
            wp_nav_menu(
                [
                    'theme_location' => 'primary_navigation',
                    'container' => false,
                    'container_class' => false,
                    'menu_class' => 'footer-menu',
                    'fallback_cb' => 'false',
                    'depth' => 1,
                ]
            );
            ?>
        </nav>
        <div class="footer-copyright">
            Copyright &copy; <?php esc_html_e(date('Y '));
            bloginfo('name'); ?>
        </div>
        <section class="starter-theme-credits">
            <p><a href="https://www.freepik.com/free-vector/bird-colorful-logo-gradient-vector_28267842.htm" target="_blank" rel="noreferrer">Logo by logturnal</a> on Freepik</p>
        </section>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
