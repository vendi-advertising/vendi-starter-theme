<?php
/**
 * Template for displaying search forms
 */
?>
    <form role="search" method="get" class="search-form" action="<?php esc_attr_e(home_url('/')); ?>">
        <label>
            <span class="screen-reader-text">Search for:</span>
            <input type="search" class="search-field" placeholder="Search …" value="" name="s" />
        </label>
        <input type="submit" class="search-submit" value="Search" />
    </form>
