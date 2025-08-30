<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<?php if ($this->checkSpotlight('roof', 'roof-1, roof-2, roof-3, roof-4')) : ?>
    <!-- Roof -->
    <div class="t3-sl t3-sl-roof">
        <div class="container">
            <?php $this->spotlight('roof', 'roof-1, roof-2, roof-3, roof-4') ?>
        </div>
    </div>
    <!-- //Roof -->
<?php endif ?>