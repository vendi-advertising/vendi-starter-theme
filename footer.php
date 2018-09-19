	<!-- begin footer -->
		<footer>
			<div class="footer">
				<div class="footer-region region">
					<div class="footer-top">
                        <div class="footer-resources">
                            <h6>HEADER</h6>
                            <ul>
                                <li><a target="_blank" href="#">SITE</a></li>
                                <li><a target="_blank" href="#">SITE</a></li>
                                <li><a target="_blank" href="#">SITE</a></li>
                                <li><a target="_blank" href="#">SITE</a></li>
                            </ul>
                        </div>
						<div class="footer-nav">
						<?php
                            wp_nav_menu(
                                array(
                                    'theme_location'  => 'footer_nav',
                                    'container'       => false,
                                    'container_class' => false,
                                    'menu_class'      => 'footer-menu',
                                    'fallback_cb'     => 'false',
                                    'depth'           => 1,
                                )
                            );
				        ?>
						</div>
					</div>
					<div class="footer-bottom">
                        <div class="footer-logo">
							<a href="<?php echo home_url(); ?>">
								<img alt="" src="<?php echo get_template_directory_uri(); ?>/images/LOGO.svg" />
							</a>
						</div>
                        <div class="footer-bottom-right">
                            <div class="footer-social">
                                <ul>
                                    <li><a href="#" class="facebook_account"><i class="fab fa-facebook-f fa-fw"></i></a></li>
                                    <li><a href="#" class="twitter_account"><i class="fab fa-twitter fa-fw"></i></a></li>
                                    <li><a href="#" class="google_plus_account"><i class="fab fa-google-plus-g fa-fw"></i></a></li>
                                    <li><a href="#" class="linkedin"><i class="fab fa-linkedin-in fa-fw"></i></a></li>
                                </ul>
                            </div>
                            <div class="footer-copyright">
                                Copyright &copy; <?php echo date( 'Y ' ); ?> COMPANY, Inc.
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</footer>
		<!-- end footer -->

        <?php wp_footer(); ?>
  </body>
</html>
