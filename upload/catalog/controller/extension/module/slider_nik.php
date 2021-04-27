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
						$thumb = $this->resizeToWidth($slide['image'], 300, $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
						$thumb = $this->resizeToWidth('placeholder.png', 300, $setting['width'], $setting['height']);
					}

					$data['slides'][] = array(
					    'index'       => $slides_counter,
						'name'        => $slide['name'],
						'text' 		  => html_entity_decode($slide['text']),
						'image'       => $image,
						'thumb'       => $thumb,
						'link'        => $slide['link'],
					);
                    $slides_counter++;
				}
			}
		}

        $data['module'] = $module++;

		return $this->load->view('extension/module/slider_nik', $data);
	}

	protected function resizeToWidth($image, $width, $imageWidth, $imageHeight) {
        $this->load->model('tool/image');

        $ratio = $width / $imageWidth;
        $height = $imageHeight * $ratio;

        return $this->model_tool_image->resize($image, $width, $height);
    }
}