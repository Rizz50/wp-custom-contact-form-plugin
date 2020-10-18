<?php

class Custom_Contact_Form_Class extends WP_List_Table {

  function __construct(){
    global $status, $page;

    parent::__construct( array(
      'singular'  => __( 'contact_form_data', 'myCustomContactFormClass' ),     //singular name of the listed records
      'plural'    => __( 'contact_forms_data', 'myCustomContactFormClass' ),   //plural name of the listed records
      'ajax'      => false        //does this table support ajax?
    ));
    add_action( 'admin_head', array( &$this, 'admin_header' ) );            
  }

  function no_items() {
    _e( 'No data found.' );
  }

  function column_default( $item, $column_name ) {
    switch( $column_name ) {
      case 'id': 
      case 'first_name':
      case 'last_name':
      case 'email':
      case 'contact':
      case 'message':
        return $item[ $column_name ];
      default:
        return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
    }
  }

  function get_columns(){
    $columns = array(
      'cb'        => '<input type="checkbox" />',
      'first_name' => __( 'First Name', 'myCustomContactFormClass' ),
      'last_name' => __( 'Last Name', 'myCustomContactFormClass' ),
      'email'    => __( 'Email', 'myCustomContactFormClass' ),
      'contact'      => __( 'Contact', 'myCustomContactFormClass' ),
      'message'      => __( 'Message', 'myCustomContactFormClass' )
    );
    return $columns;
  }

  function column_name($item){
    $actions = array(
      // 'edit'      => sprintf('<a href="?page=%s&action=%s&contact_form_data=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
      'delete'    => sprintf('<a href="?page=%s&action=%s&contact_form_data=%s">Delete</a>',$_REQUEST['page'],'single_delete',$item['id']),
    );
    return sprintf('%1$s %2$s', $item['name'], $this->row_actions($actions) );
  }

  function get_bulk_actions() {
    $actions = array(
      'delete'    => 'Delete'
    );
    return $actions;
  }

  public function process_bulk_action() {
    
    $action = $this->current_action();
    switch ( $action ) {
        case 'delete':
            global $wpdb;
            foreach($_POST['contact_form_data'] as $id) {
              $wpdb->query("DELETE FROM ".$wpdb->prefix . "cf7_data WHERE id = $id");
            }
            //wp_die( 'Delete something' );
            break;
        case 'save':
            wp_die( 'Save something' );
            break;
        default:
            // do nothing or something else
            return;
            break;
    }
    exit;

    return;
}

  function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="contact_form_data[]" value="%s" />', $item['id']
    );    
  }

  function prepare_items() {
    global $wpdb;
    $get_cf7_data = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix . "custom_form_data", ARRAY_A);
    $columns = $this->get_columns();
    $hidden = array();
    $sortable = array();
    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $get_cf7_data;;
    $this->process_bulk_action();
  }
} //class