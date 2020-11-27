<?php
class ControllerExtensionModuleSlider extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/slider');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('slider', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

//            echo "<pre>";
//            var_dump($this->request->post);
//            echo "</pre>";

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = '';
		}

		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/slider', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/slider', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/slider', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/slider', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

        if (isset($this->request->post['effect'])) {
            $data['effect'] = $this->request->post['effect'];
        } elseif (!empty($module_info)) {
            $data['effect'] = $module_info['effect'];
        } else {
            $data['effect'] = '';
        }

        if (isset($this->request->post['display_control'])) {
            $data['display_control'] = $this->request->post['display_control'];
        } elseif (!empty($module_info)) {
            $data['display_control'] = $module_info['display_control'];
        } else {
            $data['display_control'] = '';
        }

        if (isset($this->request->post['autoplay'])) {
            $data['autoplay'] = $this->request->post['autoplay'];
        } elseif (!empty($module_info)) {
            $data['autoplay'] = $module_info['autoplay'];
        } else {
            $data['autoplay'] = '';
        }

        if (isset($this->request->post['padding_top'])) {
            $data['padding_top'] = $this->request->post['padding_top'];
        } elseif (!empty($module_info)) {
            $data['padding_top'] = $module_info['padding_top'];
        } else {
            $data['padding_top'] = '';
        }

        if (isset($this->request->post['padding_left'])) {
            $data['padding_left'] = $this->request->post['padding_left'];
        } elseif (!empty($module_info)) {
            $data['padding_left'] = $module_info['padding_left'];
        } else {
            $data['padding_left'] = '';
        }

        $this->load->model('tool/image');

        $slides = array();

        if(!empty($module_info)) {
            foreach ($module_info['slide_name'] as $key => $value) {
                $slides[] = array(
                    'name'  => $value,
                    'thumb' => $this->model_tool_image->resize($module_info['slide_image'][$key], 100, 100),
                    'link'  => $module_info['slide_link'][$key]
                );
            }
        }

        echo "<pre>";
        var_dump($slides);
        echo "</pre>";

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['width'])) {
			$data['width'] = $this->request->post['width'];
		} elseif (!empty($module_info)) {
			$data['width'] = $module_info['width'];
		} else {
			$data['width'] = 200;
		}

		if (isset($this->request->post['speed'])) {
			$data['speed'] = $this->request->post['speed'];
		} elseif (!empty($module_info)) {
			$data['speed'] = $module_info['speed'];
		} else {
			$data['speed'] = 3;
		}

		if (isset($this->request->post['height'])) {
			$data['height'] = $this->request->post['height'];
		} elseif (!empty($module_info)) {
			$data['height'] = $module_info['height'];
		} else {
			$data['height'] = 200;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/slider', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/slider')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		return !$this->error;
	}
}
