<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>

<?php if ($this->countModules('head-search')) : ?>
    <!-- HEAD SEARCH -->
    <div class="head-search <?php $this->_c('head-search') ?>">
        <jdoc:include type="modules" name="<?php $this->_p('head-search') ?>" style="raw" />
    </div>
    <!-- //HEAD SEARCH -->
<?php endif ?>
