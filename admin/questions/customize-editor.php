<?php

// exit if file is called directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_enqueue_scripts', 'thet_question_edit_page_custom_js' );

function thet_question_edit_page_custom_js(){

    global $pagenow, $post;

    if ( isset( $post ) && $post->post_type === 'questions' && $pagenow === 'post.php' ){

        wp_register_script( 'thet_json_editor', plugin_dir_url( __FILE__ ) . 'js/jsoneditor.min.js', [], '1.0.0', true );
        wp_register_script( 'thet_questions_editor_js', plugin_dir_url( __FILE__ ) . 'js/question-editor.js', ['thet_json_editor'], '1.0.0', true );
        wp_localize_script( 'thet_questions_editor_js', 'thetQEditor', [
            
                'nonce' => wp_create_nonce( 'ajax-nonce' ),
                'data' => get_post_meta( $post->ID, 'question_data' ),

            ] );

        wp_register_style( 'thet_questions_editor_css', plugin_dir_url( __FILE__ ) . 'css/questions-editor.css' );

        wp_enqueue_script( 'thet_json_editor' );
        wp_enqueue_script( 'thet_questions_editor_js' );
        wp_enqueue_style( 'thet_questions_editor_css' );

        add_action( 'edit_form_after_editor', 'thet_question_editor_adjustments' );
          
    }

}

function thet_question_editor_adjustments(){

    global $post;
    
    ?>
        
        <div style="margin-top: 2rem;">

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                <th scope="row"><label>Order:</label></th>
                <td><input type="number" id="order" value="<?php echo $post->menu_order ?>" class="regular-text"></td>
                </tr>
            </tbody>
        </table>
            <div>
                <h2>Beam Content:</h2>
            </div>

            <div class="thet-question-editor-wrapper">

            </div>
        </div>
        

    <?php

}

add_action('admin_head', 'thet_customize_questions_columns_width');
function thet_customize_questions_columns_width() {
    global $post_type;
    if ( 'questions' == $post_type ) {
        ?>
            <style type="text/css">
                .column-menu_order {
                    width: 100px;
                    text-align: center;
                }
            </style>
        <?php
    }
}

add_action( 'manage_questions_posts_columns', 'thet_customize_questions_columns' );

function thet_customize_questions_columns( $columns ){

    $new_columns = [
            'cb' => $columns['cb'],
            'menu_order' => 'Order',
            'title' => $columns['title'],
            'date' => $columns['date']
        ];
    return $new_columns;

}

add_action( 'manage_questions_posts_custom_column', 'thet_questions_custom_columns_values', 10, 2 );

function thet_questions_custom_columns_values( $column, $post_id ){

    switch ( $column ){

        case 'menu_order': echo get_post( $post_id )->menu_order; break;

    }

}

add_action( 'pre_get_posts', 'thet_customize_questions_query');

function thet_customize_questions_query( $query ){

    if ( $query->is_main_query() && $query->get('post_type') === 'questions' ){

        $query->set( 'orderby', 'menu_order' );
        $query->set( 'order', 'ASC' );

    }

}
