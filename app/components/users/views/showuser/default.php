<?php
/**
 * Профиль пользователя
 */
// запрет прямого доступа
defined('_JOOS_CORE') or die();
?>
<h3 class="title-page">О себе</h3>
<div class="artist-about b-gray">

    <div class="b-70 b-left">
        <strong>Возраст:</strong> <?php echo $user->extra()->birthdate ? Users::get_age($user->extra->birthdate) : ' не указан'; ?>
    <br/>
        <strong>Местоположение:</strong> <?php echo $user->extra()->location ? $user->extra->location : ' не указано'; ?>
    <br/>

        <?php $about = json_decode($user->extra->about); ?>
        <br/><?php echo isset($about->about) ? strip_tags($about->about) : '' ?>
    </div>

<?php
    $user_contacts = json_decode($user->extra->contacts);
    $contacts_types = UsersExtra::get_contacts_types();
    ?>
    <?php if (count($user_contacts)): ?>
    <div class="b-20 b-right">
        <?php foreach ($user_contacts as $type => $val): ?>
        <?php if ($val[0] != ''): ?>
            <div><strong><?php echo $contacts_types[$type] ?>:</strong> <?php echo implode(', ', $val) ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</div>


<?php //if($user->gid==9 && isset($about->about2)): ?>
<?php //echo $about->about2;?>
<?php //endif; ?>
