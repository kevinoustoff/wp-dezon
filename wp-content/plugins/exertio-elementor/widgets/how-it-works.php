<?php
namespace ElementorExertio\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class How_it_Works extends Widget_Base {
	
	public function get_name() {
		return 'how-it-works';
	}
	
	public function get_title() {
		return __( 'How It Works', 'exertio-elementor' );
	}
	
	public function get_icon() {
		return 'eicon-product-stock';
	}

	public function get_categories() {
		return [ 'exertio' ];
	}
	
	public function get_script_depends() {
		return [ '' ];
	}
	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	 
	 
	protected function _register_controls() {
		$this->start_controls_section(
			'heading section',
			[
				'label' => esc_html__( 'Section Heading', 'exertio-elementor' ),
			]
		);
		$this->add_control(
			'heading_text',
			[
				'label' => __( 'Heading Text', 'exertio-elementor' ),
				'label_block' =>true,
				'placeholder' => __( 'Main Heading text', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => __( 'Subtitle Here', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 4,
				'placeholder' => __( 'Type your description here', 'exertio-elementor' ),
			]
		);
		$this->add_control(
			'heading_style',
			[
				'label' => __( 'Heading Style', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'label_block' =>true,
				'options' => [
					'center'  => __( 'Center', 'exertio-elementor' ),
					'left' => __( 'Left', 'exertio-elementor' ),
				],
			]
		);
		$this->add_control(
			'heading_side_btn',
			[
				'label' => __( 'Want to show heading side button', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'label_block' =>true,
				'options' => [
					'yes'  => __( 'Yes', 'exertio-elementor' ),
					'no' => __( 'NO', 'exertio-elementor' ),
				],
				'conditions' => [
                    'terms' => [
                        [
                            'name' => 'heading_style',
                            'operator' => 'in',
                            'value' => [
                                'left',
                            ],
                        ],
                    ],
                ]
			]
		);
		$this->add_control(
			'heading_side_btn_text',
			[
				'label' => __( 'Provide button text', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' =>true,
				'conditions' => [
                    'terms' => [
                        	[
                            'name' => 'heading_side_btn',
                            'operator' => 'in',
                            'value' => [
                                'yes',
                            ],
                        ],
						[
                            'name' => 'heading_style',
                            'operator' => 'in',
                            'value' => [
                                'left',
                            ],
                        ],
                    ],
                ]
			]
		);
		$this->add_control(
			'heading_side_btn_link',
			[
				'label' => __( 'Provide button link', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'label_block' =>true,
				'conditions' => [
					'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'heading_side_btn',
                            'operator' => 'in',
                            'value' => [
                                'yes',
                            ],
                        ],
						[
                            'name' => 'heading_style',
                            'operator' => 'in',
                            'value' => [
                                'left',
                            ],
                        ],
                    ],
                ]
			]
		);
		
		$this->end_controls_section();

		
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Section ', 'exertio-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'side_image_position',
			[
				'label' => __( 'Side Image Position', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'label_block' =>true,
				'options' => [
					'left'  => __( 'Left', 'exertio-elementor' ),
					'right' => __( 'Right', 'exertio-elementor' ),
				],
			]
		);
		
		$repeater->add_control(
			'side_image',
			[
				'label' => __( 'Side Image', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			]
		);
		$repeater->add_control(
			'main-heading',
			[
				'label' => __( 'Main Heading', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' =>true,
			]
		);
		$repeater->add_control(
			'sub-heading',
			[
				'label' => __( 'Sub Heading', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' =>true,
			]
		);
		$repeater->add_control(
			'description',
			[
				'label' => __( 'Description', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'label_block' =>true,
			]
		);
		$repeater->add_control(
			'section_btn',
			[
				'label' => __( 'Want to show heading button', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'center',
				'label_block' =>true,
				'options' => [
					'yes'  => __( 'Yes', 'exertio-elementor' ),
					'no' => __( 'NO', 'exertio-elementor' ),
				],
			]
		);
		$repeater->add_control(
			'section_btn_text',
			[
				'label' => __( 'Provide button text', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' =>true,
				'conditions' => [
                    'terms' => [
                        	[
                            'name' => 'section_btn',
                            'operator' => 'in',
                            'value' => [
                                'yes',
                            ],
                        ],
                    ],
                ]
			]
		);
		$repeater->add_control(
			'section_btn_link',
			[
				'label' => __( 'Provide button link', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'label_block' =>true,
				'conditions' => [
					'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'section_btn',
                            'operator' => 'in',
                            'value' => [
                                'yes',
                            ],
                        ],
                    ],
                ]
			]
		);

		
		
		
		$this->add_control(
			'section_hiw_list',
			[
				'label' => __( 'Section List', 'exertio-elementor' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),

			]
		);

		$this->end_controls_section();

	}
	
		protected function render() {
		$settings = $this->get_settings_for_display();

		$params['heading_text'] = $settings['heading_text'];
		$params['heading_description'] = $settings['heading_description'];
		$params['heading_style'] = $settings['heading_style'];
		$params['heading_side_btn'] = $settings['heading_side_btn'];
		$params['heading_side_btn_text'] = $settings['heading_side_btn_text'];
		$params['heading_side_btn_link'] = $settings['heading_side_btn_link'];
		
		
		$params['section_hiw_list'] = $settings['section_hiw_list'];

			echo exertio_element_howit_works($params);
		}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _content_template() {
			
	}
}