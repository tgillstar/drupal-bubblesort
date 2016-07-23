<?php
/**
 * Created by PhpStorm.
 * User: tiffanygill
 * Date: 5/25/16
 */
/**
* @file
* Contains \Drupal\demo\Form\Bubblesort\BubblesortFormBase.
*/

namespace Drupal\bubblesort\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\SessionManagerInterface;
use Drupal\user\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BubblesortFormBase extends FormBase
{

    /**
     * @var \Drupal\user\PrivateTempStoreFactory
     */
    protected $tempStoreFactory;

    /**
     * @var \Drupal\Core\Session\SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var \Drupal\Core\Session\AccountInterface
     */
    private $currentUser;

    /**
     * @var \Drupal\user\PrivateTempStore
     */
    protected $store;

    /**
     * Constructs a \Drupal\demo\Form\Bubblesort\BubblesortFormBase.
     *
     * @param \Drupal\user\PrivateTempStoreFactory $temp_store_factory
     * @param \Drupal\Core\Session\SessionManagerInterface $session_manager
     * @param \Drupal\Core\Session\AccountInterface $current_user
     */
    public function __construct(PrivateTempStoreFactory $temp_store_factory, SessionManagerInterface $session_manager, AccountInterface $current_user)
    {
        $this->tempStoreFactory = $temp_store_factory;
        $this->sessionManager = $session_manager;
        $this->currentUser = $current_user;
        $this->store = $this->tempStoreFactory->get('bubblesort');
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container)
    {
        return new static(
            $container->get('user.private_tempstore'),
            $container->get('session_manager'),
            $container->get('current_user')
        );
    }

    /**
     * {@inheritdoc}.
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        // Start a manual session for anonymous users.
        if ($this->currentUser->isAnonymous() && !isset($_SESSION['bubblesort_session'])) {
            $_SESSION['bubblesort_session'] = true;
            $this->sessionManager->start();
        }

        $form = array();
        $form['actions']['#type'] = 'actions';

        return $form;
    }
}