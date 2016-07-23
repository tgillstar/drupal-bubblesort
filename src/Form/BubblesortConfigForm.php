<?php
/**
 * Created by PhpStorm.
 * User: tiffanygill
 * Date: 5/25/16
 */

/**
 * @file
 * Contains \Drupal\bubblesort\Form\BubblesortForm.
 */
namespace  Drupal\bubblesort\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;

class BubblesortConfigForm extends ConfigFormBase {
    /**
     * Constructor for BubblesortConfigForm.
     *
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     * The factory for configuration objects.
     */
    public function __construct(ConfigFactoryInterface $config_factory) {
        parent::__construct($config_factory);
    }
    /**
     * Gets the configuration names that will be editable.
     *
     * @return array
     * An array of configuration object names that are editable if called in
     * conjunction with the trait's config() method.
     */
    protected function getEditableConfigNames() {
        return ['bubblesort.settings'];
    }
    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'bubblesort_admin_form';
    }

    /**
     * Form constructor.
     *
     * @param array $form
     * An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     * The current state of the form.
     *
     * @return array
     * The form structure.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $bubblesort_config = $this->config('bubblesort.settings');

        $form['integers_needed'] = array(
            '#type' => 'textfield',
            '#title' => t('Integers needed'),
            '#size' => 10,
            '#maxlength' => 255,
            '#default_value' => $bubblesort_config->get('integers_needed'),
            '#description' => t('How many random integers do you want to generate?'),
        );
        $form['integer_min'] = array(
            '#type' => 'textfield',
            '#title' => t('Minimum integer'),
            '#size' => 10,
            '#maxlength' => 255,
            '#default_value' => $bubblesort_config->get('integer_min'),
            '#description' => t('What is the lowest integer that can be generated?'),
        );
        $form['integer_max'] = array(
            '#type' => 'textfield',
            '#title' => t('Maximum integer'),
            '#size' => 10,
            '#maxlength' => 255,
            '#default_value' => $bubblesort_config->get('integer_max'),
            '#description' => t('What is the lowest integer that can be generated?'),
        );
        return parent::buildForm($form, $form_state);
    }

    /**
     * Form submission handler.
     *
     * @param array $form
     * An associative array containing the structure of the form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     * The current state of the form.
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        parent::submitForm($form, $form_state);

        $this->config('bubblesort.settings')
            ->set('integers_needed', $form_state->getValue(array('integers_needed')))
            ->set('integer_min', $form_state->getValue(array('integer_min')))
            ->set('integer_max', $form_state->getValue(array('integer_max')))
            ->save();
    }
}