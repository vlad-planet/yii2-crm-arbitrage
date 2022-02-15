<?php
	use backend\models\Menu;
	use backend\models\AuthAssignment;
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?//=$assetDir?>/backend/web/images/infinity.png" alt="Infinitum Workspace" class="brand-image img-circle">
        <span class="brand-text font-weight-light"><span style="font-weight:500 !important;">INFINITUM</span> WORKS</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=$assetDir?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><? echo \Yii::$app->user->identity->email; ?></a>
            </div>
        </div>
        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->
        <!-- Sidebar Menu -->
        <nav class="mt-2">
<?
	$sgmnt = AuthAssignment::findOne(['user_id' => Yii::$app->user->identity->id]);
	$items =[];
	$tm_sl =[];

	foreach(Menu::SECTION as $id => $name){
		if($id != null ){
			
			if($id != 1){
				$hd =['label' => $name, 'header' => true];
				array_push($items,$hd);
			}
			
			$mn = Menu::find()->where(['section_id' => $id])->orderBy(['id' => SORT_ASC])->all();
			foreach($mn as $mn){
				if($mn->url == '*'){$pt=[];
					$sl = Menu::find()->where(['parent_id' => $mn->id])->orderBy(['id' => SORT_ASC])->all();
					foreach($sl as $sl){
							if($sgmnt->item_name == 'admin')
							{
								$pt[] = ['label' => $sl->name,  'url' => ['/'.$sl->url], 'iconClass' => $sl->icon];
							}
						if(isset($sgmnt->item_id) && in_array($sl->id, unserialize($sgmnt->item_id))){
							if($sgmnt->item_name == 'user' && $mn->section_id !=  3){
								$pt[] = ['label' => $sl->name,  'url' => ['/'.$sl->url], 'iconClass' => $sl->icon];
							}
							/*
							if($sgmnt->item_name == 'admin')
							{
								$pt[] = ['label' => $sl->name,  'url' => ['/'.$sl->url], 'iconClass' => $sl->icon];
							}
							*/
						}
					}
					$tm_sl =[
						'label' => $mn->name,
						'icon' 	=> $mn->icon,
						'items' => $pt,
					];
					array_push($items,$tm_sl);
					$tm_sl = [];
				}else{
					
					if($sgmnt->item_name == 'admin')
					{
						$tm = ['label' => $mn->name,  'url' => ['/'.$mn->url], 'iconClass' => $mn->icon];
						array_push($items,$tm);
					}
					
					if(isset($sgmnt->item_id)){
						$tm = [];
						if(in_array($mn->id, unserialize($sgmnt->item_id))){
							if($sgmnt->item_name == 'user' && $mn->section_id !=  3){
								$tm = ['label' => $mn->name,  'url' => ['/'.$mn->url], 'iconClass' => $mn->icon];
								array_push($items,$tm);
							}
							/*
							if($sgmnt->item_name == 'admin')
							{
								$tm = ['label' => $mn->name,  'url' => ['/'.$mn->url], 'iconClass' => $mn->icon];
							}
							*/
						}
					}
				}
			}
		}
	}
?>

			<?php
			/*
				$items =[
							/*
							[
								'label' => 'Starter Pages',
								'icon' => 'tachometer-alt',
								'badge' => '<span class="right badge badge-info">2</span>',
								'items' => [
									['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
									['label' => 'Inactive Page', 'iconStyle' => 'far'],
								]
							],
							['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right badge badge-danger">New</span>'],
							*

							/*
							['label' => 'Login', 'url' => ['site/login'], 'icon' => 'sign-in-alt', 'visible' => Yii::$app->user->isGuest],
							['label' => 'Gii',  'icon' => 'file-code', 'url' => ['/gii'], 'target' => '_blank'],
							['label' => 'Debug', 'icon' => 'bug', 'url' => ['/debug'], 'target' => '_blank'],
							*/

							/*
							['label' => 'MULTI LEVEL EXAMPLE', 'header' => true],
							['label' => 'Level1'],
							[
								'label' => 'Level1',
								'items' => [
									['label' => 'Level2', 'iconStyle' => 'far'],
									[
										'label' => 'Level2',
										'iconStyle' => 'far',
										'items' => [
											['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
											['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle'],
											['label' => 'Level3', 'iconStyle' => 'far', 'icon' => 'dot-circle']
										]
									],
									['label' => 'Level2', 'iconStyle' => 'far']
								]
							],
							['label' => 'Level1'],
							
							['label' => 'LABELS', 'header' => true],
							['label' => 'Important', 'iconStyle' => 'far', 'iconClassAdded' => 'text-danger'],
							['label' => 'Warning', 'iconClass' => 'nav-icon far fa-circle text-warning'],
							['label' => 'Informational', 'iconStyle' => 'far', 'iconClassAdded' => 'text-info'],
						];
				*/	

            echo \hail812\adminlte\widgets\Menu::widget([
                'items' => $items,
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>