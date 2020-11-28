<?php
	namespace Drupal\birth_details\Form;

	use Drupal\Core\Form\FormBase;
	use Drupal\Core\Form\FormStateInterface;

	use Drupal\Core\Entity\EntityTypeManager;
	use Drupal\Core\Session\AccountProxyInterface;

	use Psr\Container\ContainerInterface;

	class BirthDetails extends FormBase {

		protected $currentUser;
		protected $nodeManager;


		public function __construct(EntityTypeManager $entity_type_manager, AccountProxyInterface $current_user) {

			$this->currentUser = $current_user;
			$this->nodeManager = $entity_type_manager->getStorage('node');

		}

		public static function create(ContainerInterface $container){
			return new static(
				$container->get('entity_type.manager'),
				$container->get('current_user')
			);
		}

		public function getFormId(){
			return 'birth_data';
		}

		public function buildForm(array $form, FormStateInterface $form_state){

			$form['title'] =  [
				'#type' => 'textfield',
				'#description' => $this->t('Enter your name here!'),
			];

			$form['field_birth_location'] =  [
				'#type' => 'textfield',
				'#description' => $this->t('Enter your birth location here!'),
			];

			$form['actions']['#type'] = 'actions';
			$form['actions']['submit'] = [
				'#type' => 'submit',
				'#value' => $this->t('Save data'),
				
			];

			return $form;
		}

		public function validateForm(array &$form, FormStateInterface $form_state){

		}

		public function submitForm(array &$form, FormStateInterface $form_state){

			$node = $this->nodeManager->create([
				'type' => 'birth_data',
				'title' => $form_state->getValue('title'),
				'uid' => $this->currentUser->id(),
				'status' => 1,
			]);

			$node->field_birth_location->value = $form_state->getValue('field_birth_location');
			$node->save();

			$this->messenger()->addStatus($this->t('Form submitted successfully. Thanks %name', [
										    '%name' => $form_state->getValue('title'),
										  ]));
		}
	}