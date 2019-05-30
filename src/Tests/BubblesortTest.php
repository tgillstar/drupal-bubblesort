<?php
/**
 * Created by PhpStorm.
 * User: tiffanygill
 * Date: 2/26/16
 * Time: 8:22 AM
 */

/**
 * @file
 * Contains \Drupal\bubblesort\Tests\BubblesortTest.
 */

namespace Drupal\bubblesort\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provide some basic tests for our Bubblesort form.
 * @group bubblesort
 */
class BubblesortTest extends WebTestBase {

    /**
     * Modules to install.
     * @var array
     */
    public static $modules = ['node', 'block', 'bubblesort'];

    /**
     * A simple user with 'access content' permission
     */
    private $user;

    /**
     * Perform any initial set up tasks that run before every test method
     */
    public function setUp() {
        parent::setUp();
        $this->user = $this->drupalCreateUser(array('access content'));
    }
    /**
     * Tests that 'bubblesort/form' returns a 200 OK response.
     */
    public function testBubblesortPageExists() {
        //Login
        $this->drupalLogin($this->user);

        //Generate test
        $this->drupalGet('bubblesort/form');
        $this->assertResponse(200);
    }
}
