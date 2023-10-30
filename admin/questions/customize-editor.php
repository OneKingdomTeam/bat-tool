<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_enqueue_scripts', 'thet_question_edit_page_custom_js' );

function thet_question_edit_page_custom_js(){

    global $pagenow, $post;

    if ( isset( $post ) && $post->post_type === 'questions' && ( $pagenow === 'post.php' || $pagenow === 'post-new.php' )){

        wp_register_script( 'thet_questions_editor_js', plugin_dir_url( __FILE__ ) . 'js/question-editor.js', [], false, true );
        wp_localize_script( 'thet_questions_editor_js', 'thetQEditor', [
            
                'nonce' => wp_create_nonce( 'ajax-nonce' ),
                'data' => get_post_meta( $post->ID, 'question_data', true ),

            ] );

        wp_register_style( 'thet_questions_editor_css', plugin_dir_url( __FILE__ ) . 'css/questions-editor.css' );

        wp_enqueue_script( 'thet_questions_editor_js' );
        wp_enqueue_style( 'thet_questions_editor_css' );

        add_action( 'edit_form_after_editor', 'thet_question_editor_adjustments' );

    }
}

function thet_question_editor_adjustments(){

    global $post;
    
    $name_divider = '___';
    
    ?>
        
        <div style="margin-top: 2rem;">

            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <th scope="row"><label>Order:</label></th>
                        <td><input type="number" name="thet-order" value="<?php echo $post->menu_order ?>" class="regular-text"></td>
                    </tr>
                </tbody>
            </table>

            <?php 

                $question_data = get_post_meta( $post->ID, 'question_data', true );

                foreach( $question_data as $segment_key => $segment_value ){

                    ?>
                        <h3><?php echo $segment_key ?></h3>
                        <table class="form-table" role="presentation">
                            <tbody>
                    <?php
                    
                    foreach( $segment_value as $question_key => $question_value ){

                        ?><tr><td><hr></td><td><h4><?php echo $question_key ?></h4></td></tr><?php

                        foreach( $question_value as $current_question_key => $current_question_value ){

                            if ( gettype( $current_question_value ) !== 'array' ){
                                ?>
                                    <tr>
                                        <th scope="row"><label><?php echo $current_question_key ?></label></th>
                                        <td>
                                        <?php
                                            if ( $current_question_key === 'description' ) {

                                               ?>
                                                   <textarea rows="5" cols="38" name="<?php echo $segment_key . $name_divider . $question_key . $name_divider . $current_question_key ?>"><?php echo $current_question_value ?></textarea>
                                                <?php  

                                            } else {
                                                ?>
                                                    <input type="text" name="<?php echo $segment_key . $name_divider . $question_key . $name_divider . $current_question_key ?>" value="<?php echo $current_question_value ?>" class="regular-text">
                                                <?php  
                                            }  

                                        ?>
                                        </td>
                                    </tr>
                                <?php
                            }
                
                            if ( gettype( $current_question_value ) === 'array' ){

                                ?><tr><td><h4><?php echo $current_question_key ?></h4></td></tr><?php
                                foreach( $current_question_value as $radio_key => $radio_value ){

                                ?>
                                    <tr>
                                        <th scope="row"><label><?php echo $radio_key ?></label></th>
                                        <td><input type="text" name="<?php echo $segment_key . $name_divider . $question_key . $name_divider . $current_question_key . $name_divider . $radio_key ?>" value="<?php echo $radio_value ?>" class="regular-text"></td>
                                    </tr>
                                <?php

                                }

                            }

                        };


                    }
                        ?>
                            </tbody>
                        </table>
                        <?php
        
                };

            ?>

        </div>
    <?php


}

add_action('save_post_questions', 'thet_save_question_changes');


function thet_save_question_changes(){
    
    $file = fopen( plugin_dir_path(__FILE__) . 'log.txt', "w" ) or die('tadafdada');
    ob_start();
    var_dump( $_POST );
    fwrite( $file, ob_get_clean() );
    fclose( $file );
    unset( $file );

    if ( isset( $_POST['action'] ) && $_POST['action'] === 'editpost' ){

        $question_data = get_post_meta( $_POST['post_ID'], 'question_data', true );
        foreach( $_POST as $post_key => $post_value ){

           if( str_contains( $post_key, '___' ) ){

                $path_to_data = explode( '___', $post_key );

                $current_path = &$question_data;
                foreach( $path_to_data as $step ){

                    if ( isset( $current_path[$step] ) ){

                        $current_path = &$current_path[$step];

                    } 

                }

                $current_path = wp_strip_all_tags( $post_value, true );

            }

        };

        update_post_meta( $_POST['post_ID'], 'question_data', $question_data );
        unset( $question_data );
    
        if ( isset( $_POST['thet-order'] )) {

            $args = [
                'ID' => intval( $_POST['ID'] ),
                'menu_order' => intval( $_POST['thet-order'] ),
            ];

            wp_update_post( $args, true );

        }

    }


}

