<?php
    get_header();
    $bio = (new WikiBiographie())->get_biographie(get_the_ID());
    ?>
<div class="default-max-width">

    <a href="<?php echo get_post_type_archive_link('biographie'); ?>">Toutes les biographies</a>

    <h2><?php _e("Biographie de : "); ?><?php the_title(); ?></h2>

    <table class="bio-table">
        <?php if(!empty($bio['first_name'])): ?>
            <tr>
                <th>Prénom</th>
                <td>
                    <?php echo esc_attr($bio['first_name']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['last_name'])): ?>
            <tr>
                <th>Nom</th>
                <td>
                    <?php echo esc_attr($bio['last_name']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['image'])): ?>
            <tr>
                <th>Photo</th>
                <td>
                    <img src="<?php echo esc_url($bio['image']); ?>" alt="Photo de <?php the_title(); ?>" style="max-width: 200px;">
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['pseudo'])): ?>
            <tr>
                <th>Pseudonyme</th>
                <td>
                    <?php echo esc_attr($bio['pseudo']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['dob'])): ?>
            <tr>
                <th>Date de naissance</th>
                <td>
                    <?php echo esc_attr($bio['dob']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['pob'])): ?>
            <tr>
                <th>Lieu de naissance</th>
                <td>
                    <?php echo esc_attr($bio['pob']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['dod'])): ?>
            <tr>
                <th>Date de décès</th>
                <td>
                    <?php echo esc_attr($bio['dod']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['pod'])): ?>
            <tr>
                <th>Lieu de décès</th>
                <td>
                    <?php echo esc_attr($bio['pod']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['occupation'])): ?>
            <tr>
                <th>Occupation</th>
                <td>
                    <?php echo esc_attr($bio['occupation']); ?>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['website'])): ?>
            <tr>
                <th>Site web</th>
                <td>
                    <a href="<?php echo esc_url($bio['website']); ?>" target="_blank"><?php echo esc_url($bio['website']); ?></a>
                </td>
            </tr>
        <?php endif; ?>
        <?php if(!empty($bio['introduction']) || !empty($bio['introduction_complement'])): ?>
            <tr>
                <th>Description</th>
                <td>
                    <?php echo esc_textarea($bio['introduction']); ?>
                    <?php if(!empty($bio['introduction_complement'])): ?>
                        <?php echo esc_textarea($bio['introduction_complement']); ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endif; ?>
    </table>

    <?php if(!empty($bio['wikipedia_url'])): ?>
        <div class="source">
            <a target="_blank" href="https://creativecommons.org/licenses/by-sa/3.0/deed.fr">Contenu soumis à la licence CC-BY-SA 3.0</a>. Source : Article <em><a target="_blank" href="<?php echo esc_url($bio['wikipedia_url']) ?>"><?php echo the_title(); ?></a></em> de <a target="_blank" href="https://www.wikipedia.org/">Wikipédia</a></div>
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>