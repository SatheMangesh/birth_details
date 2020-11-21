<?php

namespace Drupal\birth_details\Controller;
use Drupal\Core\Controller\ControllerBase;

class birthdetailsController extends ControllerBase {

	public function getcontent(){
		return [
			'#type' => 'markup',
			'#markup' => $this->t('Enter your details here!'),
		];
	}
}