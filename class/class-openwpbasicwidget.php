<?php
/**
 * Create a basic Widget to display a Agenda from Openagenda.com on a sidebar.
 *
 * @package openagenda-basic-widget
 */

/**
 * Class OpenwpBasicWidget
 */
class OpenwpBasicWidget extends WP_Widget {
	/**
	 * OpenwpBasicWidget constructor.
	 */
	public function __construct() {
		$widget_args = array(
			'classname'   => 'Openagenda Basic Widget',
			'description' => __( 'Display an Openagenda.com\'s Agenda in your WordPress Sidebar with a beautiful widget', 'wp-openagenda' ),
		);
		parent::__construct(
			'openwp_basic_widget',
			'Openagenda Basic Widget',
			$widget_args
		);
		add_action( 'widgets_init', array( $this, 'init_openwp_basic_widget' ) );
	}

	/**
	 * Display the Widget in Front Office.
	 *
	 * @param array $args Argument of Widget.
	 * @param array $instance Settings of widget.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		echo $args['before_title'];

		echo apply_filters( 'widget_title', $instance['title'] );

		echo $args['after_title'];

		$openwp = new OpenAgendaApi();

		$openwp_data = $openwp->thfo_openwp_retrieve_data( $instance['slug'], $instance['nb'] );

		$lang = $instance['lang'];

		$openwp->openwp_basic_html( $openwp_data, $lang );
	}

	/**
	 * Widget Settings
	 *
	 * @param array $instance Store Widget Settings.
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : ''; ?>
		<p>
			<label for="<?php echo $this->get_field_name( 'title' ); ?>"> <?php esc_attr_e( 'Title:' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type = "text" value="<?php echo $title; ?>"/>
		</p>


		<p>
			<label
				for="<?php echo $this->get_field_name( 'slug' ); ?>"> <?php _e( 'OpenAgenda Slug:', 'openagenda-wp' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'slug' ); ?>" name="<?php echo $this->get_field_name( 'slug' ); ?>" type="text"  value="<?php echo $instance['slug']; ?>"/>

		</p>
		<p>
			<label
				for="<?php echo $this->get_field_name( 'nb' ); ?>"> <?php _e( 'Number of events:', 'openagenda-wp' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'nb' ); ?>"
			       name="<?php echo $this->get_field_name( 'nb' ); ?>" type="text"
			       value="<?php echo $instance['nb']; ?>"/>

		</p>
		<p>
			<label
				for="<?php echo $this->get_field_name( 'lang' ); ?>"> <?php _e( 'Languages of events:', 'openagenda-wp' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'nb' ); ?>"
			       name="<?php echo $this->get_field_name( 'lang' ); ?>" type="text"
			       value="<?php echo $instance['lang']; ?>"/>

		</p>
		<?php
	}

	/**
	 * Initialize a new Widget.
	 */
	public function init_openwp_basic_widget() {
		register_widget( 'OpenwpBasicWidget' );
	}

}
new OpenwpBasicWidget();
