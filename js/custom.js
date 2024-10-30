jQuery(document).ready(function() {
    'use strict';

    // script for the toggle sidebar
    var span_full = jQuery('.toggleSidebar .dashicons');
    var show_sidebar = localStorage.getItem('mmqw-sidebar-display');
    if( ( null !== show_sidebar || undefined !== show_sidebar ) && ( 'hide' === show_sidebar ) ) {
        jQuery('.all-pad').addClass('hide-sidebar');
        span_full.removeClass('dashicons-arrow-right-alt2').addClass('dashicons-arrow-left-alt2');
    } else {
        jQuery('.all-pad').removeClass('hide-sidebar');
        span_full.removeClass('dashicons-arrow-left-alt2').addClass('dashicons-arrow-right-alt2');
    }
    jQuery(document).on( 'click', '.toggleSidebar', function(){
        jQuery('.all-pad').toggleClass('hide-sidebar');
        if( jQuery('.all-pad').hasClass('hide-sidebar') ){
            localStorage.setItem('mmqw-sidebar-display', 'hide');
            span_full.removeClass('dashicons-arrow-right-alt2').addClass('dashicons-arrow-left-alt2');
            jQuery('.all-pad .mmqw-section-right').css({'-webkit-transition': '.3s ease-in width', '-o-transition': '.3s ease-in width',  'transition': '.3s ease-in width'});
            jQuery('.all-pad .mmqw-section-left').css({'-webkit-transition': '.3s ease-in width', '-o-transition': '.3s ease-in width',  'transition': '.3s ease-in width'});
            setTimeout(function() {
                jQuery('#dotsstoremain .dotstore_plugin_sidebar').css('display', 'none');
            }, 300);
        } else {
            localStorage.setItem('mmqw-sidebar-display', 'show');
            span_full.removeClass('dashicons-arrow-left-alt2').addClass('dashicons-arrow-right-alt2');
            jQuery('.all-pad .mmqw-section-right').css({'-webkit-transition': '.3s ease-out width', '-o-transition': '.3s ease-out width',  'transition': '.3s ease-out width'});
            jQuery('.all-pad .mmqw-section-left').css({'-webkit-transition': '.3s ease-out width', '-o-transition': '.3s ease-out width',  'transition': '.3s ease-out width'});
            jQuery('#dotsstoremain .dotstore_plugin_sidebar').css('display', 'block');
        }
    });
        
    jQuery('#no_post_add').keyup(function() {
        var value = jQuery(this).val();
        value = value.replace(/^(0*)/, '');
        jQuery(this).val(value);
    });
    // End Subscribe Functionality
    jQuery(document).ready(function() {
        jQuery('#type').change(function() {
            var type = jQuery('#type').val();
            if (type === 'page') {
                jQuery('.parent_page_id_tr').show();
                jQuery('.template_name_tr').show();
            } else if(type === 'e-landing-page') {
                jQuery('.template_name_tr').show();
                jQuery('.parent_page_id_tr').hide();
            } else {
                jQuery('.parent_page_id_tr').hide();
                jQuery('.template_name_tr').hide();
            }

        });

        // script for plugin rating
        jQuery(document).on('click', '.dotstore-sidebar-section .content_box .et-star-rating label', function(e){
            e.stopImmediatePropagation();
            var rurl = jQuery('#et-review-url').val();
            window.open( rurl, '_blank' );
        });
    });

    // add currunt menu class in main manu
    jQuery(window).load(function () {
        jQuery('a[href="admin.php?page=mass-pages-posts-creator"]').parent().addClass('current wp-has-current-submenu');
        jQuery('a[href="admin.php?page=mass-pages-posts-creator"]').addClass('current');
    });

    function pages_content_getContent(editor_id, textarea_id) {
        if (typeof editor_id === 'undefined') {
            editor_id = wpActiveEditor;
        }
        if (typeof textarea_id === 'undefined') {
            textarea_id = editor_id;
        }
        if (jQuery('#wp-' + editor_id + '-wrap').hasClass('tmce-active') && tinyMCE.get(editor_id)) {
            return tinyMCE.get(editor_id).getContent();
        } else {
            return jQuery('#' + textarea_id).val();
        }
    }

    jQuery('#btn_submit').click(function() {
        var prefix_word = jQuery('#page_prefix').val();
        var pages_list = jQuery('#pages_list').val();
        var pages_content = pages_content_getContent('pages_content');
        var parent_page_id = jQuery('#page-filter').val();
        var template_name = jQuery('#template_name').val();
        var type = jQuery('#type').val();
        var postfix_word = jQuery('#page_postfix').val();
        var comment_status = jQuery('#comment_status').val();

        var page_status = jQuery('#page_status').val();
        var authors = jQuery('#authors').val();
        var excerpt_content = jQuery('#excerpt_content').val();
        var no_post_add = jQuery('#no_post_add').val();
        var mass_pages_posts_creator = jQuery('#mass_pages_posts_creator').val();

        if (pages_list.length === 0 || pages_list === '') {
            alert('Please enter list of Pages..');
            event.preventDefault();
            return false;
        }

        if (type === 'none') {
            alert('Please select the type..');
            event.preventDefault();
            return false;
        }
        jQuery.ajax({
            type: 'POST',
            data: {
                action: 'mpc_ajax_action',
                prefix_word: prefix_word,
                postfix_word: postfix_word,
                pages_list: pages_list,
                pages_content: pages_content,
                parent_page_id: parent_page_id,
                template_name: template_name,
                type: type,
                page_status: page_status,
                authors: authors,
                excerpt_content: excerpt_content,
                no_post_add: no_post_add,
                comment_status: comment_status,
                security: mass_pages_posts_creator
            },
            url: adminajax.ajaxurl,
            dataType: 'json',
            success: function(response) {
                if (response) {
                    jQuery('#createForm').css('display', 'none');
                    jQuery('#message').addClass('view');
                    jQuery('html,body').animate({scrollTop: 0}, 'slow');
                    jQuery('#message').html('Pages/Posts Succesfully Created.. ');
                    responseTable(jQuery('#result').get(0),response); 
                } else {
                    jQuery('#message').addClass('view');
                    jQuery('#message').html('Something goes wrong..');
                }
            }
        });

    });

    jQuery( '#page-filter' ).select2({
        ajax: {
            url: adminajax.ajaxurl,
            dataType: 'json',
            delay: 250,
            data: function( params ) {
                return {
                    value: params.term,
                    action: 'page_finder_ajax',
                    security: jQuery('#mass_pages_posts_creator').val(),
                };
            },
            processResults: function( data ) {
                var options = [];
                if ( data ) {
                    jQuery.each( data, function( index, text ) {
                        options.push( { id: text[ 0 ], text: allowSpeicalCharacter( text[ 1 ] ) } );
                    });

                }
                return {
                    results: options
                };
            },
            cache: true
        },
        minimumInputLength: 3
    });

    /** Dynamic Promotional Bar START */
    jQuery(document).on('click', '.dpbpop-close', function () {
        var popupName = jQuery(this).attr('data-popup-name');
        setCookie( 'banner_' + popupName, 'yes', 60 * 24 * 7);
        jQuery('.' + popupName).hide();
    });

    jQuery(document).on('click', '.dpb-popup .dpb-popup-meta a', function () {
        var promotional_id = jQuery(this).parents().find('.dpbpop-close').attr('data-bar-id');

        //Create a new Student object using the values from the textfields
        var apiData = {
            'bar_id' : promotional_id
        };

        jQuery.ajax({
            type: 'POST',
            url: adminajax.dpb_api_url + 'wp-content/plugins/dots-dynamic-promotional-banner/bar-response.php',
            data: JSON.stringify(apiData),// now data come in this function
            dataType: 'json',
            cors: true,
            contentType:'application/json',
            
            success: function (data) {
                console.log(data);
            },
            error: function () {
            }
         });
    });
    /** Dynamic Promotional Bar END */

    /** Plugin Setup Wizard Script START */
    // Hide & show wizard steps based on the url params 
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('require_license')) {
        jQuery('.ds-plugin-setup-wizard-main .tab-panel').hide();
        jQuery( '.ds-plugin-setup-wizard-main #step5' ).show();
    } else {
        jQuery( '.ds-plugin-setup-wizard-main #step1' ).show();
    }
    
    // Plugin setup wizard steps script
    jQuery(document).on('click', '.ds-plugin-setup-wizard-main .tab-panel .btn-primary:not(.ds-wizard-complete)', function () {
        var curruntStep = jQuery(this).closest('.tab-panel').attr('id');
        var nextStep = 'step' + ( parseInt( curruntStep.slice(4,5) ) + 1 ); // Masteringjs.io

        if( 'step5' !== curruntStep ) {
            // Youtube videos stop on next step
            jQuery('iframe[src*="https://www.youtube.com/embed/"]').each(function(){
               jQuery(this).attr('src', jQuery(this).attr('src'));
               return false;
            });
            
            jQuery( '#' + curruntStep ).hide();
            jQuery( '#' + nextStep ).show();   
        }
    });

    // Get allow for marketing or not
    if ( jQuery( '.ds-plugin-setup-wizard-main .ds_count_me_in' ).is( ':checked' ) ) {
        jQuery('#fs_marketing_optin input[name="allow-marketing"][value="true"]').prop('checked', true);
    } else {
        jQuery('#fs_marketing_optin input[name="allow-marketing"][value="false"]').prop('checked', true);
    }

    // Get allow for marketing or not on change     
    jQuery(document).on( 'change', '.ds-plugin-setup-wizard-main .ds_count_me_in', function() {
        if ( this.checked ) {
            jQuery('#fs_marketing_optin input[name="allow-marketing"][value="true"]').prop('checked', true);
        } else {
            jQuery('#fs_marketing_optin input[name="allow-marketing"][value="false"]').prop('checked', true);
        }
    });

    // Complete setup wizard
    jQuery(document).on( 'click', '.ds-plugin-setup-wizard-main .tab-panel .ds-wizard-complete', function() {
        if ( jQuery( '.ds-plugin-setup-wizard-main .ds_count_me_in' ).is( ':checked' ) ) {
            jQuery( '.fs-actions button'  ).trigger('click');
        } else {
            jQuery('.fs-actions #skip_activation')[0].click();
        }
    });

    // Send setup wizard data on Ajax callback
    jQuery(document).on( 'click', '.ds-plugin-setup-wizard-main .fs-actions button', function() {
        var wizardData = {
            'action': 'mppc_plugin_setup_wizard_submit',
            'survey_list': jQuery('.ds-plugin-setup-wizard-main .ds-wizard-where-hear-select').val(),
            'nonce': adminajax.setup_wizard_ajax_nonce
        };

        jQuery.ajax({
            url: adminajax.ajaxurl,
            data: wizardData,
            success: function ( success ) {
                console.log(success);
            }
        });
    });
    /** Plugin Setup Wizard Script End */

    /** Upgrade Dashboard Script START */
    // Dashboard features popup script
    jQuery(document).on('click', '.dotstore-upgrade-dashboard .premium-key-fetures .premium-feature-popup', function (event) {
        let $trigger = jQuery('.feature-explanation-popup, .feature-explanation-popup *');
        if(!$trigger.is(event.target) && $trigger.has(event.target).length === 0){
            jQuery('.feature-explanation-popup-main').not(jQuery(this).find('.feature-explanation-popup-main')).hide();
            jQuery(this).parents('li').find('.feature-explanation-popup-main').show();
            jQuery('body').addClass('feature-explanation-popup-visible');
        }
    });
    jQuery(document).on('click', '.dotstore-upgrade-dashboard .popup-close-btn', function () {
        jQuery(this).parents('.feature-explanation-popup-main').hide();
        jQuery('body').removeClass('feature-explanation-popup-visible');
    });
    /** Upgrade Dashboard Script End */

    /** Script for Freemius upgrade popup */
    jQuery(document).on('click', '#dotsstoremain .mppc-pro-label', function(){
        jQuery('body').addClass('mppc-modal-visible');
    });
    jQuery(document).on('click', '.upgrade-to-pro-modal-main .modal-close-btn', function(){
        jQuery('body').removeClass('mppc-modal-visible');
    });
    jQuery(document).on('click', '.dots-header .dots-upgrade-btn, .dotstore-upgrade-dashboard .upgrade-now', function(e){
        e.preventDefault();
        upgradeToProFreemius( '' );
    });
    jQuery(document).on('click', '.upgrade-to-pro-modal-main .upgrade-now', function(e){
        e.preventDefault();
        jQuery('body').removeClass('mppc-modal-visible');
        let couponCode = jQuery('.upgrade-to-pro-discount-code').val();
        upgradeToProFreemius( couponCode );
    });

    // Upgrade to pro poup on premium option
    jQuery(document).on( 'change', '.mmqw-section-left #type', function() {
        let selectedOption = jQuery(this).find(':selected').val();
        if( selectedOption.includes('_in_pro') ){
            jQuery(this).find(':selected').prop('selected', false);

            jQuery('body').addClass('mppc-modal-visible');
        }
    } );
    jQuery(document).on( 'change', '.mmqw-section-left #page_status', function() {
        let selectedOption = jQuery(this).find(':selected').val();
        if( selectedOption.includes('_in_pro') ){
            jQuery(this).find(':selected').prop('selected', false);
            jQuery(this).find('option[value="pending"]').prop('selected', true);

            jQuery('body').addClass('mppc-modal-visible');
        }
    } );

    // Script for Beacon configuration
    var helpBeaconCookie = getCookie( 'mppc-help-beacon-hide' );
    if ( ! helpBeaconCookie ) {
        if ( typeof Beacon === 'function' ) {
            Beacon('init', 'afe1c188-3c3b-4c5f-9dbd-87329301c920');
            Beacon('config', {
                display: {
                    style: 'icon',
                    iconImage: 'message',
                    zIndex: '99999'
                }
            });

            // Add plugin articles IDs to display in beacon
            Beacon('suggest', ['5e03425304286364bc9338fa', '5e034b7d2c7d3a7e9ae580c4', '5e034c1d04286364bc933905', '5e0352392c7d3a7e9ae580cc', '5e034f5704286364bc933907']);

            // Add custom close icon form beacon
            setTimeout(function() {
                if ( jQuery( '.hsds-beacon .BeaconFabButtonFrame' ).length > 0 ) {
                    let newElement = document.createElement('span');
                    newElement.classList.add('dashicons', 'dashicons-no-alt', 'dots-beacon-close');
                    let container = document.getElementsByClassName('BeaconFabButtonFrame');
                    container[0].appendChild( newElement );
                }
            }, 3000);

            // Hide beacon
            jQuery(document).on('click', '.dots-beacon-close', function(){
                Beacon('destroy');
                setCookie( 'mppc-help-beacon-hide' , 'true', 24 * 60 );
            });
        }
    }
});

// Set cookies
function setCookie(name, value, minutes) {
    var expires = '';
    if (minutes) {
        var date = new Date();
        date.setTime(date.getTime() + (minutes * 60 * 1000));
        expires = '; expires=' + date.toUTCString();
    }
    document.cookie = name + '=' + (value || '') + expires + '; path=/';
}

// Get cookies
function getCookie(name) {
    let nameEQ = name + '=';
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i].trim();
        if (c.indexOf(nameEQ) === 0) {
            return c.substring(nameEQ.length, c.length);
        }
    }
    return null;
}

/** Script for Freemius upgrade popup */
function upgradeToProFreemius( couponCode ) {
    let handler;
    handler = FS.Checkout.configure({
        plugin_id: '3481',
        plan_id: '5551',
        public_key:'pk_9edf804dccd14eabfd00ff503acaf',
        image: 'https://www.thedotstore.com/wp-content/uploads/sites/1417/2023/10/Mass-Pages-Posts-Creator-For-WordPress-Banner-1.png',
        coupon: couponCode,
    });
    handler.open({
        name: 'Mass Pages Posts Creator For WordPress',
        subtitle: 'You’re a step closer to our Pro features',
        licenses: jQuery('input[name="licence"]:checked').val(),
        purchaseCompleted: function( response ) {
            console.log (response);
        },
        success: function (response) {
            console.log (response);
        }
    });
}

function createtag(element,tag,attributes){
    var createElement=document.createElement(tag);
    setAllAttributes(createElement,attributes);
    element.appendChild(createElement);
    return document.getElementById(attributes.id);    
}

function responseTable(element,response){
    var table=createtag(element,'table',{'id': 'datatable'});
    var thead=createtag(table,'thead',{'id': 'datahead'});
    var headtitles=['Page/Post Id','Page/Post Name','Page/Post Status', 'URL'];
    createCustomRow(thead,'th',headtitles,{'id':'datath'});
    var tbody=createtag(table,'tbody',{'id': 'databody'});
    for(var i=0; i<response.length;i++){
        data=Object.values(response[i]);
        createCustomRow(tbody,'td',data,{'id' : 'datatd-'+i});
    }
}
function createCustomRow(element,celltype,data,attributes){
    var tr=createtag(element,'tr',attributes);
    for(var i=0;i<data.length;i++){
        var cell=createtag(tr,celltype,{'id': attributes.id+'-'+celltype+'-'+i});
        var text = document.createTextNode(data[i]);
        cell.appendChild(text);
        tr.appendChild(cell);
    }
}
function setAllAttributes(element,attributes){
    Object.keys(attributes).forEach(function (key) {
        element.setAttribute(key, attributes[key]);
        // use val
    });
    return element;
}
function allowSpeicalCharacter(str){
    return str.replace('&#8211;','–').replace('&gt;','>').replace('&lt;','<').replace('&#197;','Å');    
}
