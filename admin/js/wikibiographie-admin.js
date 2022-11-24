(function( $ ) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
     *
     * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
     *
     * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */

    $(function() {

        $('#empty_cache').click(function(e) {
            $('#empty_cache_loading').show();
            $('#empty_cache').attr('disabled', 'disabled');
            e.preventDefault();
            $.post(ajaxurl, {action: 'empty_cache'}, function(response) {
                let msg = '';
                if (response === 'success') {
                    msg = '✓ Données en cache supprimées avec succès';
                } else {
                    msg = '✓' + response;
                }
                $('#empty_cache_section span.result_msg').removeClass('error').addClass('success').html(msg).show();
            })
                .fail(function(e) {
                    $('#empty_cache_section span.result_msg').removeClass('success').addClass('error').html(e.responseJSON.error).show();
                })
                .always(function() {
                    $('#empty_cache').attr('disabled', false);
                    $('#empty_cache_loading').hide();
                });
        });

        $('#refresh_wikidata').click(function(e) {

            resetForm();

            e.preventDefault();

            const mapping = {
                'dateOfBirth': 'date_of_birth',
                'dateOfDeath': 'date_of_death',
                'entityUrl': 'entity_url',
                'image': 'image',
                'introduction': 'introduction',
                'firstName': 'first_name',
                'lastName': 'last_name',
                'occupation': 'occupation',
                'placeOfBirth': 'place_of_birth',
                'placeOfDeath': 'place_of_death',
                'pseudonym': 'pseudonym',
                'website': 'website',
            }

            const data = {
                'action': 'fetch_wikidata',
                'wikipediaUrl': $('[name=_biographie_custom_wikipedia_url]').val()
            };

            $.post(ajaxurl, data, function(response) {
                $.each(mapping, function(key, value) {
                    if(response[key]) {
                        if(key === 'image') {
                            $('#_biographie_wiki_image').attr('src', response[key]);
                            $(`[name=_biographie_wiki_${value}]`).val(response[key]);
                        } else {
                            $(`[name=_biographie_wiki_${value}]`).val(ucFirst(response[key]));
                        }

                    }
                });
                let fullName = [response['firstName'], response['lastName']].filter(function (v) {return v != null;}).join(' ');

                if(!$('#title').val()) {
                    $('#title').val(fullName);
                    $('#title-prompt-text').hide();
                }
                $('#_biographie_wiki_image').attr('alt', fullName);
            })
            .fail(function(e) {
                $('#wikipedia-metabox-table p.error').html(e.responseJSON.error).show();
            })
            .always(function() {
                $('#refresh_wikidata').attr('disabled', false);
                $('#bio-loading').hide();
            });
        });

        function resetForm() {
            $(this).attr('disabled', 'disabled');
            $('#wikipedia-metabox-table p.error').hide();
            $('#bio-loading').show();
            $('[name^=_biographie_wiki_]').val('');
            $('[id=_biographie_wiki_image]').attr('src', '');
        }

        function ucFirst(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

    });

})( jQuery );
