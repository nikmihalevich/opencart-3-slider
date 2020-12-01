<?php
class ControllerExtensionModuleSliderNik extends Controller {
	public function index($setting) {
        static $module = 0;
		$this->load->language('extension/module/slider_nik');

		$this->load->model('tool/image');

		$data['products'] = array();

        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/swiper.min.css');
        $this->document->addStyle('catalog/view/javascript/jquery/swiper/css/opencart.css');
        $this->document->addScript('catalog/view/javascript/jquery/swiper/js/swiper.jquery.js');

		if (!$setting['speed']) {
			$setting['speed'] = 3;
		}

		$data = $setting;

		if (!empty($setting['slides'])) {
			$data['slides'] = array();
			$slides_counter = 0;
			foreach ($setting['slides'] as $slide) {

				if ($slide) {
					if ($slide['image']) {
						$image = $this->model_tool_image->resize($slide['image'], $setting['width'], $setting['height']);
						$thumb = $this->model_tool_image->resize($slide['image'], 75, 75);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
						$thumb = $this->model_tool_image->resize('placeholder.png', 75, 75);
					}

					$data['slides'][] = array(
					    'index'       => $slides_counter,
						'name'        => $slide['name'],
						'text' 		  => utf8_substr(strip_tags(html_entity_decode($slide['text'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'image'       => $image,
						'thumb'       => $thumb,
						'link'        => $slide['link'],
					);
                    $slides_counter++;
				}
			}
		}

        $data['module'] = $module++;

		if ($data['slides']) {
			return $this->load->view('extension/module/slider_nik', $data);
		}
	}
}