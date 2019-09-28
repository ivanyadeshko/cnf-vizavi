<?php

/**
 * @var $this \controllers\SiteController
 * @var $productsComposite \models\ProductsComposite
 * @var $group \models\Group
 */
?>
<?php foreach ($productsComposite->findForGroup($group->id) AS $product) { ?>
    <li><b><?= $group->loadProductDescription($product); ?></b></li>
<?php } ?>
