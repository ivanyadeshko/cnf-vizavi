<?php

/**
 * @var $this \controllers\SiteController
 * @var $groupsComposite \models\GroupsComposite
 * @var $productsComposite \models\ProductsComposite
 * @var $errors array
 */
?>
<?php if ($errors) { ?>
    <ul style="color: #ff4d4d; font-weight: bold">
        File processing errors:
        <?php foreach ($errors AS $error) { ?>
            <li><?= $error; ?></li>
        <?php } ?>
    </ul>
<?php } ?>

<ul>
<?= $this->renderPartial('_group_block', [
    'groups' => $groupsComposite->findUpperGroups(),
    'groupsComposite' => $groupsComposite,
    'productsComposite' => $productsComposite,
]); ?>
</ul>