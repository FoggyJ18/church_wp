<?php

// This file only contains the definition of the activation function.
// It keeps the main plugin file from getting cluttered.

function volunteer_mgmt_create_database_tables() {
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    
    // table for volunteering event types
    $table_name = $wpdb->prefix . 'volunteering_event_template';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
      `id` INT NOT NULL AUTO_INCREMENT,
      `short_name` VARCHAR(45) NOT NULL,
      `description` VARCHAR(250) NULL,
      `max_volunteers` INT NULL,
      `weekday` VARCHAR(45) NULL,
      `time` TIME NULL,
      `waiver_reqd` TINYINT NOT NULL,
      `waiver` VARCHAR(500) NULL,
      PRIMARY KEY (`id`),
      UNIQUE INDEX `short_name_UNIQUE` (`short_name` ASC) VISIBLE) $charset_collate;";

    dbDelta( $sql );
    
    // table for tracking volunteering events
    $event_table_name = $wpdb->prefix . 'volunteering_event';
    // $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $event_table_name (
      `id` INT NOT NULL AUTO_INCREMENT,
      `display_name` VARCHAR(45) NOT NULL,
      `event_date` DATETIME NOT NULL,
      `event_duration` INT NOT NULL DEFAULT 0,
      `volunteer_count` INT NOT NULL DEFAULT 0,
      `waiver_reqd` TINYINT NOT NULL,
      `waiver` VARCHAR(500) NULL,
      PRIMARY KEY (`id`) ) $charset_collate;";

    dbDelta( $sql );
    
    // table for tracking volunteers
    $volunteer_table_name = $wpdb->prefix . 'volunteer';
    // $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $volunteer_table_name (
      `id` INT NOT NULL AUTO_INCREMENT,
      `name` VARCHAR(75) NOT NULL,
      `emailaddress` VARCHAR(100) NULL,
      `phonenumber` VARCHAR(16) NULL,
      `waivers_signed` VARCHAR(500) NULL,
      `signup_count` INT NULL DEFAULT 0,
      `attendance_count` INT NULL DEFAULT 0,
      PRIMARY KEY (`id`) ) $charset_collate;";

    dbDelta( $sql );
    
    // table for event sign-ups
    $signup_table_name = $wpdb->prefix . 'signup';
    $event_fk_constraint_name = $wpdb->prefix . 'event_id';
    $volunteer_fk_constraint_name = $wpdb->prefix . 'volunteer_id';
    // $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $signup_table_name (
      `id` INT NOT NULL AUTO_INCREMENT,
      `bringing_meal` TINYINT NULL DEFAULT 0,
      `admin_override_hours` INT NULL DEFAULT 0,
      `event_id` int NOT NULL DEFAULT 0,
      `volunteer_id` int NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`),
      INDEX `event_id_idx` (`event_id` ASC) VISIBLE,
      INDEX `volunteer_id_idx` (`volunteer_id` ASC) VISIBLE,
      CONSTRAINT $event_fk_constraint_name
        FOREIGN KEY (`event_id`)
        REFERENCES $event_table_name (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
      CONSTRAINT $volunteer_fk_constraint_name
        FOREIGN KEY (`volunteer_id`)
        REFERENCES $volunteer_table_name (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION) $charset_collate;";

    dbDelta( $sql );
}