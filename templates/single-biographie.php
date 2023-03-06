<?php
    get_header();
    $bio = (new WikiBiographie())->get_biographie(get_the_ID());
    ?>
<div class="default-max-width">

    <a href="<?php echo get_post_type_archive_link('biographie'); ?>"><?php _e('All biographies', 'wikibiographie'); ?></a>

    <h2><?php _e("Biography of : ", 'wikibiographie'); ?><?php the_title(); ?></h2>

    <table class="bio-table">
        <?php if(!empty($bio['first_name'])): ?>
            <tr>
                <th><?php _e('First name', 'wikibiographie'); ?></th>
                <td>
                    <?php echo esc_attr($bio['first_name']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['last_name'])): ?>
            <tr>
                <th><?php _e('Last name', 'wikibiographie'); ?></th>
                <td>
                    <?php echo esc_attr($bio['last_name']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['image'])): ?>
            <tr>
                <th><?php _e('Photo', 'wikibiographie'); ?></th>
                <td>
                    <img src="<?php echo esc_url($bio['image']); ?>" alt="<?php _e('Photo of', 'wikibiographie'); ?> <?php the_title(); ?>" style="max-width: 200px;">
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['pseudo'])): ?>
            <tr>
                <th><?php _e('Nickname', 'wikibiographie'); ?></th>
                <td>
                    <?php echo esc_attr($bio['pseudo']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['dob'])): ?>
            <tr>
                <th><?php _e('Date of birth', 'wikibiographie'); ?></th>
                <td>
                    <?php echo esc_attr($bio['dob']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['pob'])): ?>
            <tr>
                <th><?php _e('Place of birth', 'wikibiographie'); ?></th>
                <td>
                    <?php echo esc_attr($bio['pob']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['dod'])): ?>
            <tr>
                <th><?php _e('Date of death', 'wikibiographie'); ?></th>
                <td>
                    <?php echo esc_attr($bio['dod']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['pod'])): ?>
            <tr>
                <th><?php _e('Place of death', 'wikibiographie'); ?></th>
                <td>
                    <?php echo esc_attr($bio['pod']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['occupation'])): ?>
            <tr>
                <th><?php _e('Occupation', 'wikibiographie'); ?></th>
                <td>
                    <?php echo esc_attr($bio['occupation']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['website'])): ?>
            <tr>
                <th><?php _e('Official website', 'wikibiographie'); ?></th>
                <td>
                    <a href="<?php echo esc_url($bio['website']); ?>" target="_blank"><?php echo esc_url($bio['website']); ?></a>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['introduction']) || !empty($bio['introduction_complement'])): ?>
            <tr>
                <th><?php _e('Description', 'wikibiographie') ?></th>
                <td>
                    <?php echo html_entity_decode($bio['introduction']); ?>
                    <?php if(!empty($bio['introduction_complement'])): ?>
                        <?php echo html_entity_decode($bio['introduction_complement']); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>
    </table>

    <?php if(!empty($bio['wikipedia_url'])): ?>
        <div class="source">
            <a target="_blank" href="https://creativecommons.org/licenses/by-sa/3.0/deed.fr"><?php _e('Content comply to CC-BY-SA 3.0 licence', 'wikibiographie'); ?></a>. <?php _e('Source : Article', 'wikibiographie'); ?> <em><a target="_blank" href="<?php echo esc_url($bio['wikipedia_url']) ?>"><?php echo the_title(); ?></a></em> de <a target="_blank" href="https://www.wikipedia.org/"><?php _e('Wikipedia', 'wikibiographie'); ?></a></div>
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>