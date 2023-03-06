<?php get_header(); ?>

<div class="default-max-width">

    <h2><?php _e("Biographies"); ?></h2>

    <div>
        <form action="<?php echo get_post_type_archive_link('biographie'); ?>" method="get">
            <input type="text" name="s" id="search" value="<?php the_search_query(); ?>" />
            <input type="submit" value="<?php _e('Search', 'wikibiographie'); ?>" />
            <input type="hidden" value="biographie" name="post_type" id="post_type" />
        </form>
    </div>

    <?php if ( have_posts() ) : ?>
        <ul>
            <?php while ( have_posts() ) : the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>

    <?php echo paginate_links(); ?>

</div>

<?php get_footer(); ?>
