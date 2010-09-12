<?php
/**
 * @copyright (C) 2010 raplos
 * Модуль для тестов
 */
defined('_VALID_MOS') or die();

global $my;

JHTML::loadJqueryPlugins('jquery.validate');

require_once 'formvalidator.php';

$validator = new FormValidator();
$validator->addValidation("username", "req", "Надо ввести имя");
$validator->addValidation("username", "minlen=2", "Минимум для имени - 2 символа");
$validator->addValidation("username", "maxlen=10", "Максимум для имени - 10 символов");
$validator->addValidation("email", "email", "Это не мыло");
$validator->addValidation("email", "req", "А где мыло?");
$val = $validator->ValidateForm();

if(isset($_POST['submit'])) {
    if($val) {
        echo "<h2>Validation Success!</h2>";
        $show_form=false;
    }
    else {
        echo "<B>Validation Errors:</B>";
        $error_hash = $validator->GetErrors();
        foreach($error_hash as $inpname => $inp_err) {
            echo "<p>$inpname : $inp_err</p>\n";
        }
    }
}

$script = "
                <script language=\"javascript\">
                $(document).ready(function() {
                        var validator = $('#test_form').validate({
                                rules:" . $validator->get_js_validator('rules') . ",
                                messages:" . $validator->get_js_validator('messages') . ",
                                errorPlacement: function(error, element) {
                                    error.appendTo(element.parent().next());
                                },
                                success: function(label) {
                                        label.html('&nbsp;').addClass('checked');
                                }
                        });
                });
                </script>
                ";

echo "$script
            <div>
                <form id=\"test_form\" autocomplete=\"off\" method=\"post\" action=\"\">
                    <table>
                        <tr>
                            <td class=\"label\"><label id=\"lusername\" for=\"username\">Username</label></td>
                            <td class=\"field\"><input id=\"username\" name=\"username\" type=\"text\" value=\"\" maxlength=\"50\" /></td>
                            <td class=\"status\"></td>
                        </tr>
                        <tr>
                            <td class=\"label\"><label id=\"lemail\" for=\"email\">Email Address</label></td>
                            <td class=\"field\"><input id=\"email\" name=\"email\" type=\"text\" value=\"\" maxlength=\"150\" /></td>
                            <td class=\"status\"></td>
                        </tr>
                        <tr>
                            <td class=\"label\"><label id=\"lsignupsubmit\" for=\"signupsubmit\">Submit</label></td>
                            <td class=\"field\" colspan=\"2\">
                                <input id=\"submit\" name=\"submit\" type=\"submit\" value=\"submit\" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
";
