<div class="main-content">
    <div class="search-results-region region main-content-region">
        <h2 class="search-title">You searched for &ldquo;<?php the_search_query(); ?>&rdquo;</h2>
        <?php //******* LOOP
        if (have_posts()) {
            while (have_posts()) {
                the_post();
                ?>
                <div class="search-result">
                    <h3 class="search-title">
                        <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                    </h3>

                    <?php
                    the_excerpt();
                    ?>
                </div>
                <?php
            }
        } else {
            ?>
            <p>No results were found. Please search by another keyword</p>
            <?php
        }
        ?>

    </div>
</div>
