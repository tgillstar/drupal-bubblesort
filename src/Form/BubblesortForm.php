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
namespace Drupal\bubblesort\Form;

use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;


class BubblesortForm extends BubblesortFormBase {

    /**
     * {@inheritdoc}.
     */
    public function getFormId() {
        return 'bubblesort';
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state) {

        $form = parent::buildForm($form, $form_state);

        // State if this is an anonymous session or not.
        if (isset($_SESSION['bubblesort_session'])) {
            drupal_set_message("Anonymous session!");
        }
        $form['bubblesort']['#attached']['library'][] = 'bubblesort/bubblesort';

        $temp_vector = [0];
        $temp_curr_val = 0;
        $temp_current = 0;
        $data_arr = [];
        if(!(is_null($this->store->get('currentValue')))) {
            $temp_vector = $this->store->get('vector');
            $temp_curr_val = $this->store->get('currentValue');
            $temp_current = $this->store->get('current');
            $data_arr = [$temp_vector,$temp_curr_val,$temp_current];
        }else {
            $data_arr = [$temp_vector,$temp_curr_val,$temp_current];
        }

        $form['shuffle'] = array(
            '#type' => 'submit',
            '#value' => 'Shuffle',
            '#attributes' => array(
                'id' => array(
                    'btnShuffle'
                ),
            ),
            '#submit' => array('::bubblesort_shuffle_submit_handler'),
        );
        $form['step'] = array(
            '#type' => 'submit',
            '#value' => 'Step',
            '#attributes' => array(
                'id' => array(
                    'btnStep'
                ),
            ),
            '#submit' => array('::bubblesort_step_submit_handler'),
            '#disabled' => $this->store->get('disableBtn')== true?TRUE:FALSE,
        );
        $form['details'] = array(
            '#type' => 'fieldset',
            '#tree' => TRUE,
            '#prefix' => '<div id="detailsInfo">',
            '#suffix' => '</div>'
        );
        $form['details']['current'] = array(
            '#type' => 'textfield',
            '#title' => t('Index'),
            '#size' => 10,
            '#maxlength' => 10,
            '#value' => $this->store->get('current'),
            '#attributes' => array(
                'id' => array(
                    'current'
                ),
                'class' => array(
                    'infoBlock'
                ),
            ),
        );
        $form['details']['swap'] = array(
            '#type' => 'textfield',
            '#title' => t('Swap'),
            '#size' => 10,
            '#maxlength' => 10,
            '#readonly'=> TRUE,
            '#value' => $this->store->get('currentValue'),
            '#attributes' => array(
                'id' => array(
                    'swap'
                ),
                'class' => array(
                    'infoBlock'
                ),
            ),
        );
        $form['details']['currentValue'] = array(
            '#type' => 'textfield',
            '#title' => t('Current (arr[index])'),
            '#size' => 10,
            '#maxlength' => 10,
            '#readonly'=> TRUE,
            '#value' => $this->store->get('currentValue'),
            '#attributes' => array(
                'id' => array(
                    'currentValue'
                ),
                'class' => array(
                    'infoBlock'
                ),
            ),
        );
        $form['details']['swapped'] = array(
            '#type' => 'textfield',
            '#title' => t('Swapped'),
            '#size' => 10,
            '#maxlength' => 10,
            '#readonly'=> TRUE,
            '#value' => (string)$this->store->get('swapped'),
            '#attributes' => array(
                'id' => array(
                    'swapped'
                ),
                'class' => array(
                    'infoBlock'
                ),
            ),
        );
        $form['details']['nextValue'] = array(
            '#type' => 'textfield',
            '#title' => t('Next (arr[index+1])'),
            '#size' => 10,
            '#maxlength' => 10,
            '#readonly'=> TRUE,
            '#value' => $this->store->get('nextValue'),
            '#attributes' => array(
                'id' => array(
                    'nextValue'
                ),
                'class' => array(
                    'infoBlock'
                ),
            ),
        );
        $form['details']['swapStatus'] = array(
            '#type' => 'textfield',
            '#title' => t(' '),
            '#size' => 40,
            '#maxlength' => 10,
            '#readonly'=> TRUE,
            '#value' => $this->store->get('swapStatus'),
            '#attributes' => array(
                'id' => array(
                    'swapStatus'
                ),
                'class' => array(
                    'infoBlock'
                ),
            ),
        );
        $form['visuals'] = array(
            '#tree' => TRUE,
            '#type' => 'fieldset',
            '#prefix' => '<div id="main-area">',
            '#suffix' => '</div>'
        );
        $form['visuals']['bars'] = array(
            '#tree' => FALSE,
            '#type' => 'fieldset',
            '#prefix' => '<div id="sidebar-left">',
            '#markup' => $this->graphBars($data_arr),
            '#suffix' => '</div>',
        );
        $form['visuals']['vector'] = array(
            '#tree' => FALSE,
            '#type' => 'fieldset',
            '#prefix' => '<div id="sidebar-right">',
            '#markup' => $this->integerTable($data_arr),
            '#suffix' => '</div>'
        );
        return $form;
  }

    /**
     * {@inheritdoc}
     */
    public function graphBars(array &$data)
    {
        if (!empty($data)) {
            $integerArray = $data[0];
            $selected = $data[1];

            $output = '';

            foreach ($integerArray as $line) {
                $lineWidth = (string)(((intval($line))/100)*100);
                if (!($selected == $line)) {
                    $output .= '<div class="stretchLine"><div class="bar" style="width:'.$lineWidth.'%"></div></div>';
                }else {
                    $output .= '<div class="stretchLine"><div class="bar" id="cellSelected" style="width:'.$lineWidth.'%"></div></div>';
                }
            }
        }else {
            $output = '';
        }
        return $this->t($output);
    }

    /**
     * {@inheritdoc}
     */
    public function integerTable(array &$data)
    {
        if (!empty($data)) {
            $integerArray = $data[0];
            $selected = $data[1];
            $count = 0;
            $output = '<table id="vectorList"><thead><tr><th>i<div>i</div></th><th>Value<div>Value</div></th></tr></thead>';
            $output .= '<tbody>';

            foreach ($integerArray as $integer) {
                if (!($selected == $integer)) {
                    $output .= '<tr><td>'.$count.'</td><td>'.$integer.'</td></tr>';
                }else {
                    $output .= '<tr id="cellSelected"><td>'.$count.'</td><td>'.$integer.'</td></tr>';
                }
                $count++;
            }
            $output .= '</tbody></table>';
        }else {
            $output = '<table id="vectorList"><thead><tr><th>i<div>i</div></th><th>Value<div>Value</div></th></tr></thead>';
            $output .= '<tbody><tr><td>0</td><td> </td></tr></tbody></table>';
        }
        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function clearStore()
    {

        // Initialize zero values
        $zero_value = 0;

        // Clear previous values in store
        $this->store->set('current', $zero_value);
        $this->store->set('currentValue', ' ');
        $this->store->set('nextValue', ' ');
        $this->store->set('swapStatus', ' ');
        $this->store->set('swapped', ' ');
        $this->store->set('countSwapsDone', $zero_value);
        $this->store->set('disableBtn', false);
    }
    /**
     * {@inheritdoc}
     */
    public function bubblesort_shuffle_submit_handler(array &$form, FormStateInterface $form_state)
    {
        //Clear out the store of variables
        $this->clearStore();

        // Retrieve size of array, min number of range and max number of range
        // from configuration settings
        $integersNeeded = \Drupal::config('bubblesort.settings')->get('integers_needed');
        $min = \Drupal::config('bubblesort.settings')->get('integer_min');
        $max = \Drupal::config('bubblesort.settings')->get('integer_max');

        // Vector to hold random integers
        $selectedIntegers = array();
        // Create an array of numbers based on the range set
        $tempArray = range($min, $max);
        // Loop through the array the defined number of times to construct vector
        for($i = 0; $i <$integersNeeded; $i++)
        {
            // Generate a random number within the range
            $j = rand(1, count($tempArray))-1;
            // Insert random number into vector
            $selectedIntegers[] = $tempArray[$j];
            // Removed chosen random number from range so that it i not picked again
            array_splice($tempArray, $j, 1);
        }

        //Save to initialized array of integers to the store
        $this->store->set('vector', $selectedIntegers);
        return $form['details'];
    }

    /**
     * {@inheritdoc}
     */
    public function bubblesort_step_submit_handler(array &$form, FormStateInterface $form_state)
    {
        $integersNeeded =  \Drupal::config('bubblesort.settings')->get('integers_needed');
        $integersCheck = $integersNeeded-2;
        for($step=0; $step < $integersNeeded; $step++) {

            //Get the current array in order to display it on the page;
            $listOfIntegers = $this->store->get('vector');

            // Check to see if the iteration is done before continuing
            if (!($this->store->get('current') == $integersNeeded-1)) {

                // Get the latest version of the vector array
                $listOfIntegers = $this->store->get('vector');
                // Get the current index
                $current = $this->store->get('current');

                //Get the next items in order to display them on the page;
                $currentValue = $listOfIntegers[$current];
                $nextValue = $listOfIntegers[$current + 1];
                $this->store->set('currentValue', $currentValue);
                $this->store->set('nextValue', $nextValue);

                // Check if next number is higher than current number
                // Swap the numbers if the current inter is smaller that the next integer
                if ($listOfIntegers[$current] < $listOfIntegers[$current + 1]) {
                    // Get a copy of the smaller integer
                    $temp = $listOfIntegers[$current];
                    // Switch the index of the larger integer with the location of the smaller integer in the vector
                    $listOfIntegers[$current] = $listOfIntegers[$current + 1];
                    $listOfIntegers[$current + 1] = $temp;
                    // Note that swap has occurred
                    $swapped = 'true';

                    // Display the swap status on the page display
                    $swapStatus = '$listOfIntegers[$current]<$listOfIntegers[$current+1], will swap';
                    $this->store->set('swapStatus', $swapStatus);

                } else {
                    // No swap has taken place so note that on the page display and increment count of swaps for this iteration
                    $countSwapsDone_increment = $this->store->get('countSwapsDone');
                    $countSwapsDone = $countSwapsDone_increment + 1;
                    $this->store->set('countSwapsDone', $countSwapsDone);

                    //Get the last of the items to display on the page;
                    $swapped = 'false';
                    $swapStatus = '$listOfIntegers[$current]<$listOfIntegers[$current+1], no swap';
                    $this->store->set('swapStatus', $swapStatus);
                }
                // Initialization of session variables for next iteration;
                $current_increment = $current + 1;
                $this->store->set('current', $current_increment);
                $this->store->set('vector', $listOfIntegers);
                $this->store->set('swapped', $swapped);
            }
            else {
                // If the user has click through this iteration then update session variables and break out of current loop
                $value = 0;
                $this->store->set('current', $value);
                $this->store->set('countSwapsDone', $value);
                break;
            }
            if (($this->store->get('countSwapsDone')<=$integersCheck)){
                // Disable Step button since we are done.
                $this->store->set('disableBtn', false);
                break;
            }
            else {
                $this->store->set('disableBtn', true);
            }
        }
        return $form['details'];
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {}

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {}
}