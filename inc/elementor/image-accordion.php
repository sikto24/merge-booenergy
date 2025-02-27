<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\register_controls;




class Elementor_image_accordion extends Widget_Base {

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[ 
				'label' => esc_html__( 'Content', 'textdomain' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'list',
			[ 
				'label' => esc_html__( 'Repeater List', 'textdomain' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => [ 
					[ 
						'name' => 'list_title',
						'label' => esc_html__( 'Title', 'textdomain' ),
						'type' => Controls_Manager::TEXT,
						'default' => esc_html__( 'List Title', 'textdomain' ),
						'label_block' => true,
					],
					[ 
						'name' => 'list_content',
						'label' => esc_html__( 'Content', 'textdomain' ),
						'type' => Controls_Manager::WYSIWYG,
						'default' => esc_html__( 'List Content', 'textdomain' ),
						'show_label' => false,
					],
					[ 
						'name' => 'list_color',
						'label' => esc_html__( 'Color', 'textdomain' ),
						'type' => Controls_Manager::COLOR,
						'selectors' => [ 
							'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}'
						],
					]
				],
				'default' => [ 
					[ 
						'list_title' => esc_html__( 'Title #1', 'textdomain' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'textdomain' ),
					],
					[ 
						'list_title' => esc_html__( 'Title #2', 'textdomain' ),
						'list_content' => esc_html__( 'Item content. Click the edit button to change this text.', 'textdomain' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( $settings['list'] ) {
			echo '<dl>';
			foreach ( $settings['list'] as $item ) {
				echo '<dt class="elementor-repeater-item-' . esc_attr( $item['_id'] ) . '">' . $item['list_title'] . '</dt>';
				echo '<dd>' . $item['list_content'] . '</dd>';
			}
			echo '</dl>';
		}
	}

	protected function content_template() {
		?>
		<# if ( settings.list.length ) { #>
			<dl>
				<# _.each( settings.list, function( item ) { #>
					<dt class="elementor-repeater-item-{{ item._id }}">{{{ item.list_title }}}</dt>
					<dd>{{{ item.list_content }}}</dd>
					<# }); #>
			</dl>
			<# } #>
				<?php
	}

}