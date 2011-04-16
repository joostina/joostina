<?php
/**

 *
 **/

//Запрет прямого доступа
defined('_JOOS_CORE') or die();

$questions = json_decode($poll->questions, true);
$variants = json_decode($poll->variants, true);

//_xdump($poll_results);
?>

<div class="poll">
    <h2><?php echo $poll->title ?></h2>

    <table class="poll_table">

        <?php $i = 1; foreach ($questions as $q_id => $q): ?>
        <tr>
            <td width="150"><b><?php echo $q ?></b></td>

            <td>
                <?php foreach ($variants as $v_id => $v): ?>
                <?php $result = isset($poll_results[$q_id][$v_id]) ?
                        round($poll_results[$q_id][$v_id]->result * 100 / $poll->total_users, 0) : 0;
                ?>
                <div>
                    <span class="bar-title"><?php echo $v ?></span>

                    <div class="bar-container">
                        <div style="width: <?php echo $result ?>%; display: block;" id="bar1">&nbsp;</div>
                        <strong><?php echo $result ?>%</strong>
                    </div>
                </div>

                <?php ++$i; endforeach;?>
            </td>

        </tr>
        <?php endforeach;?>
    </table>

    <br/><br/>
    <strong>Всего голосов:</strong> <?php echo $poll->total_users?>
</div>


