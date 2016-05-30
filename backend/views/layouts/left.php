<?php
//use \Yii;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use mdm\admin\components\MenuHelper;
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>Alexander Pierce</p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <ul class="sidebar-menu">
          <li class="treeview <?php if( (Yii::$app->controller->id=='route') || (Yii::$app->controller->id=='permission') || (Yii::$app->controller->id=='assignment') ||
                              (Yii::$app->controller->id=='role') || (Yii::$app->controller->id=='menu')){ echo 'active';} ?>">
            <a href="#">
              <i class="fa fa-gears"></i> <span>权限控制</span>
              <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
              <li><a href="<?=Url::to(['/admin/route/index']); ?>"><i class="fa fa-circle-o"></i> 路由</a></li>
              <li><a href="<?=Url::to(['/admin/permission/index']); ?>"><i class="fa fa-circle-o"></i> 权限</a></li>
              <li><a href="<?=Url::to(['/admin/role/index']); ?>"><i class="fa fa-circle-o"></i> 角色</a></li>
              <li><a href="<?=Url::to(['/admin/assignment/index']); ?>"><i class="fa fa-circle-o"></i> 分配</a></li>
              <li><a href="<?=Url::to(['/admin/menu/index']); ?>"><i class="fa fa-circle-o"></i> 菜单</a></li>
            </ul>
          </li>
        </ul>
        <?php
        echo Nav::widget(
          [
            "encodeLabels" => false,
              "options" => ["class" => "sidebar-menu"],
              "items" => MenuHelper::getAssignedMenu(Yii::$app->user->id),
          ]
        );
        ?>
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                    ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                    [
                        'label' => 'Same tools',
                        'icon' => 'fa fa-share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'fa fa-circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'fa fa-circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'fa fa-circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
