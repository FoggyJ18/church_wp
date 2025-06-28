<?php


// Registers the shortcodes [volunteer_info_form, event_signup_form]
function register_volunteering_shortcodes() {
    add_shortcode( 'volunteer_info_form', 'volunteer_info_form_handler' );
    add_shortcode( 'event_signup_form', 'event_signup_form_handler' );
}
add_action( 'init', 'register_volunteering_shortcodes' );

// Form handlers for registered shortcodes
function volunteer_info_form_handler() {
    global $wpdb;
    $table_name_volunteers = $wpdb->prefix . 'volunteer';
    if ( isset( $_POST['post_volunteer_form'] ) ) {
        // security step
        if ( isset( $_POST['volunteer_nonce'] ) && wp_verify_nonce( $_POST['volunteer_nonce'], 'add_volunteer_action' ) ) {
            // Sanitize the user input. Another vital security step.
            $name = sanitize_text_field( $_POST['name'] );
            $email  = sanitize_text_field( $_POST['email'] );
            $phone  = sanitize_text_field( $_POST['phone'] );
            
            if ( empty($phone) ) {
                $existing_user = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name_volunteers WHERE first_name = %s AND emailaddress LIKE %s",
                        $name, // The data for the first %s
                        $email // The data for the second %s
                    )
                );
            } else {
                $existing_user = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name_volunteers WHERE name = %s AND (emailaddress LIKE %s OR phonenumber LIKE %s)",
                        $name, // The data for the first %s
                        $email,   // The data for the second %s
                        $phone   // The data for the third %s
                    )
                );
            }

            if ( null !== $existing_user ) {
                // Use the safe $wpdb->insert() method.
                $result = $wpdb->insert(
                    $table_name_volunteers,
                    array( // Data to insert
                        'name' => $name,
                        'email'  => $email,
                        'phone'  => $phone,
                    ),
                    array( // Data formats
                        '%s', // %s for string
                        '%s',
                        '%s'
                    )
                );
                if ($result) {
                    $message = '<div class="notice notice-success">Volunteer added successfully!</div>';
                } else {
                    $message = '<div class="notice notice-error">There was an error. Please try again.</div>';
                }
            } else {
                $message = '<div class="notice notice-success">Volunteer already exists!</div>';
            }

        } else {
            // Nonce verification failed.
            $message = '<div class="notice notice-error">Security check failed.</div>';
        }
    }
    ob_start();
    ?>

    <?php if (isset($message)) {echo $message;} ?>

    <form method="POST" action="">
        <p>
            <label for="name">Name</label><br>
            <input type="text" id="name" name="name" required>
        </p>
        <p>
            <label for="email">Email Address</label><br>
            <input type="text" id="email" name="email" required>
        </p>
        <p>
            <label for="phone">Phone Number</label><br>
            <input type="text" id="phone" name="phone">
        </p>

        <?php
        // Add a WordPress Nonce field for security.
        // 'add_volunteer_action' is the action.
        // 'volunteer_nonce' is the nonce field.
        wp_nonce_field( 'add_volunteer_action', 'volunteer_nonce' );
        ?>

        <p>
            <input type="submit" name="post_volunteer_form" value="Add Volunteer">
        </p>
    </form>

    <?php
    // Return the captured HTML.
    return ob_get_clean();
}

function event_signup_form_handler() {
    return "FORM GOES HERE";
}