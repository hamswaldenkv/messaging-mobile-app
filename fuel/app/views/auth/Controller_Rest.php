<ul class="nav nav-pills">
	<li class='<?php echo Arr::get($subnav, "login" ); ?>'><?php echo Html::anchor('auth/login','Login');?></li>
	<li class='<?php echo Arr::get($subnav, "register" ); ?>'><?php echo Html::anchor('auth/register','Register');?></li>
	<li class='<?php echo Arr::get($subnav, "otp" ); ?>'><?php echo Html::anchor('auth/otp','Otp');?></li>
	<li class='<?php echo Arr::get($subnav, "Controller_Rest" ); ?>'><?php echo Html::anchor('auth/Controller_Rest','Controller Rest');?></li>

</ul>
<p>Controller Rest</p>