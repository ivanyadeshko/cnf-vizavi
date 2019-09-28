<?php

/**
 * @var $this \controllers\SiteController
 * @var $groupsComposite \models\GroupsComposite
 * @var $productsComposite \models\ProductsComposite
 * @var $groups \models\Group[]
 */
?>
<?php foreach ($groups AS $group) { ?>
<li>
    <h<?= ($group->getDepthLevel()+1); ?>><?= $group->name ?></h<?= ($group->getDepthLevel()+1); ?>>
    <ul>
        <?= $this->renderPartial('_group_products_block', [
            'group' => $group,
            'productsComposite' => $productsComposite,
        ]); ?>
        <?php if($childGroups = $groupsComposite->getChildGroups($group)) { ?>
            <?= $this->renderPartial('_group_block', [
                'groups' => $childGroups,
                'groupsComposite' => $groupsComposite,
                'productsComposite' => $productsComposite,
            ]); ?>
        <?php } ?>
    </ul>
</li>
<?php } ?>
