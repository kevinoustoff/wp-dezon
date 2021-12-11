<link href="http://fonts.cdnfonts.com/css/segoe-ui-4" rel="stylesheet">
<style>
    .notification_main_div{
        margin: 0px;
        padding: 33px 0px;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
    }
    .main_div{
        margin: 0px auto;
        width: 800px;
        display: flex;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0px 0px 30px #888888;
        background-color: #fff;
    }
    .left{
        width: 1.9%;
        /* background-image: url(1.jpg); */
        background-color: #54b8a4;
        border-right: none !important;
        background-repeat: no-repeat;
        /* background-size: 300px 300px; */
        background-position: center;
        background-attachment: scroll;
        position: relative;
        /* min-height: 400px; */
        /* min-width: 200px; */
        color: #888;
        border-right: 1px solid black;
    }
    /* .left::after {
        content: "";
        background: rgba(71, 75, 70, 0.7);
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        opacity: 0.8;
    } */
    .right{
        width: 100%;
        margin: 0px auto;
        padding: 20px 50px;
    }
    .text_input{
        width: 240px;
    }
    .input{

		
    }
    .input input{
        padding: 8px 5px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 12px;
        border-color:transparent;
        box-shadow: 0px 0px 4px #8888;
        border-radius: 10px;
		margin: 0px 3px !important;
		
    }
    .lable{
        margin-bottom: 5px;
        font-size: 16px;
		font-weight: bold;
    }
    .message{
        width: 490px;
        height: 100px;
        padding: 8px 5px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 14px;
        box-shadow: 0px 0px 4px #8888;
        border: transparent;
        border-radius: 10px;
    }
	.message1{
        width: 490px;
        height: 60px;
        padding: 8px 5px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
        font-size: 14px;
        box-shadow: 0px 0px 4px #8888;
        border: transparent;
        border-radius: 10px;
    }
    .submit_button{
        padding: 10px 60px;
        background-color: #54b8a4;
        border : 1px solid #54b8a4;
        color: #fff;
        box-shadow: 0px 0px 9px #8888;
        border-radius: 10px;
        font-weight: bold;
    }
    .submit{
        margin: 10px 0px !important;
    }
    .placeholder_panal{
        margin-bottom: 10px;
        margin-top: 10px;
    }
    .placeholder_button{
        padding: 5px 10px;
        border-radius: 20px;
        margin-bottom: 10px;
        font-size: 12px;
        background-color: #fff;
    }
    .submit_button:hover{
        cursor: pointer;
        background-color: #70c1af;
        color: #fff;
        border-color: #70c1af;
    }
    .usernameinfo_box{
        display: none;
        width: 200px;
        background-color: #fff;
        border-radius: 10px;
        border: 1px solid black;
        position: absolute;
        
    }
    .usernameinfo_box p{
        margin-block-start: 0px;
        margin-block-end: 0px;
        margin-bottom: 5px;
        font-size: 12px;
    }
    .usernameinfo_box span{
        font-size: 13px;
    }
    .username_info{margin-top: 5px;margin-left: 5px;font-weight: bold;}
    .username_info:hover + .usernameinfo_box{
        display: block;
        margin-left: 340px;
        margin-top: 0px;
        padding: 10px 10px;
        border: transparent;
        box-shadow: 0px 0px 9px #8888;
    }
    
    .note li{
        font-size: 12px;
        margin-bottom: 6px;;
    }

    .error{
        color: red;
        font-size: 10px;
        display: none;
        margin-left: 5px;
        margin-top: 5px;
    }

    .required_star{
        color: rgb(241, 6, 6);
        font-size: 12px;
    }
</style>
<?php 
/**
 * Add or update notfication field
 */
if ( isset( $_POST ) && ! empty( $_POST ) ) {

    $username = isset($_POST['username']) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : '';
    $password = isset($_POST['password']) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : '';
    $mobileno = isset($_POST['mobileno']) ? sanitize_text_field( wp_unslash( $_POST['mobileno'] ) ) : '';
    $message = isset($_POST['message']) ? sanitize_text_field( wp_unslash( $_POST['message'] ) ) : '';
    $update_notifications_arr = array();
    $flag = 1;
    if ( strlen( $username ) > 32 || strlen( $username ) < 32 ) {
        $flag = 0;
        $error_username = '';
        $error_username .='<div class="notice notice-error is-dismissible">';
        $error_username .='<p>'.esc_html( 'Please copy API username properly from website.' ).'</p>';
        $error_username .='</div>';
        echo wp_kses_post($error_username);
    }
    if ( strlen( $password ) > 32 || strlen( $password ) < 32 ) {
        $flag = 0;
        $error_password = '';
        $error_password .='<div class="notice notice-error is-dismissible">';
        $error_password .='<p>'.esc_html( 'Please copy API password properly from website.' ).'</p>';
        $error_password .='</div>';
        echo wp_kses_post($error_password);
    }
    if ( empty( $mobileno ) ) {
        $flag = 0;
        $error_mobileno = '';
        $error_mobileno .='<div class="notice notice-error is-dismissible">';
        $error_mobileno .='<p>'.esc_html( 'Please Enter Mobile Number.' ).'</p>';
        $error_mobileno .='</div>';
        echo wp_kses_post($error_mobileno);
    } elseif( strlen( $mobileno ) < 12 ) {
        $flag = 0;
        $error_mobileno = '';
        $error_mobileno .='<div class="notice notice-error is-dismissible">';
        $error_mobileno .='<p>'.esc_html( 'Please enter 12 digit number.' ).'</p>';
        $error_mobileno .='</div>';
        echo wp_kses_post($error_mobileno);
    } else {
        $numbers = explode(',', $mobileno);
        $numbers = array_filter($numbers);
        $numbers =array_map('trim', $numbers);
        $error = 0;
        $inValidNumbers = array();
        foreach($numbers as $number) {
            if ( is_numeric( $number ) ) {
                if ( strlen( $number ) < 12 ) {
                    $error++;
                    array_push($inValidNumbers,$number); 
                }
            } else {
                $error++;
                array_push($inValidNumbers,$number);
                $flag = 0;
                $error_message = '';
                $error_message .='<div class="notice notice-error is-dismissible">';
                $error_message .='<p>'.esc_html( 'Please enter valid number' ).' '.implode(", ", $inValidNumbers).'</p>';
                $error_message .='</div>';
                echo wp_kses_post($error_message);
            }
        }
        if($error != 0) { 
            $flag = 0;
            $error_message = '';
            $error_message .='<div class="notice notice-error is-dismissible">';
            $error_message .='<p>'.esc_html( 'Please enter 12 digit number of' ).' '.implode(", ", $inValidNumbers).'</p>';
            $error_message .='</div>';
            echo wp_kses_post($error_message);
        }
        if ( count( $numbers ) > 10 ) {
            $flag = 0;
            $error_message = '';
            $error_message .='<div class="notice notice-error is-dismissible">';
            $error_message .='<p>'.esc_html( 'You cannot enter more then 10 numbers' ).'</p>';
            $error_message .='</div>';
            echo wp_kses_post($error_message);
        }
    }
    if ( empty( $message ) || strlen( $message ) < 2 ) {
        $flag = 0;
        $error_message = '';
        $error_message .='<div class="notice notice-error is-dismissible">';
        $error_message .='<p>'.esc_html( 'Your message must be atleast 2 characters.' ).'</p>';
        $error_message .='</div>';
        echo wp_kses_post($error_message);
    }
    if ( $flag == 1 ) {
        $mobileno = implode(", ", $numbers);
        $update_notifications_arr = array(
            'whatso_username'   =>  $username,
            'whatso_password'   =>  $password,
            'whatso_mobileno'   =>  $mobileno,
            'whatso_message'    =>  $message,
        );
        $result = update_option( 'whatso_notifications', wp_json_encode( $update_notifications_arr ) );
        if ( $result ) {
            $success = '';
            $success .='<div class="notice notice-success is-dismissible">';
            $success .='<p>'.esc_html( 'Details update successfully.' ).'</p>';
            $success .='</div>';
            echo wp_kses_post($success);
        }
    }
}
/**
 * Get data of field
 */
$data = '';
$whatso_username = '';
$whatso_password = '';
$whatso_mobileno = '';
$whatso_message = '';
if ( ! empty( get_option( 'whatso_notifications' ) ) ) {
    $data = get_option( 'whatso_notifications' );
    $data = json_decode( $data );
    $whatso_username = $data->whatso_username;
    $whatso_password = $data->whatso_password;
    $whatso_mobileno = $data->whatso_mobileno;
    $whatso_message = $data->whatso_message;
}
?>
<div class="notification_main_div">
    <div class="main_div">
        <div class="left"></div>
        <div class="right">
            <form class="form_div" method="post">
				<div class="row" style="display: flex;">
				<div class="col-md-12">
                <h1><?php esc_html_e( 'WhatsApp Notification', 'whatso' ); ?></b></h1>
				</div>
				
				<div class="col-md-3" style="margin-left:280px;padding-top:15px;">
				<?php
				echo '<img src="https://d15jx6omahps38.cloudfront.net/images/whatso-new-logo.png" style="max-width: 110px;"/>'; ?>
				</div>
				</div>
                <p><?php esc_html_e( 'Hurray! Now you can receive a WhatsApp message when an order is placed on your store. Use the below form to setup message.', 'whatso' ); ?></p>
                <div class="input" style="display: flex;">
				<div class="lable col-md-6">
                    <label style="padding-left:3px;"><?php esc_html_e( 'Username', 'whatso' ); ?></label><span class="required_star">*</span>
                </div>
				
				<div class="lable col-md-6" style="margin-left:27%;">
                    <label><?php esc_html_e( 'Password', 'whatso' ); ?></label><span class="required_star">*</span>
                </div>
				</div>
                <div class="input" style="display: flex;">
                    <div class="col-md-6">
                        <input type="text" id="username" name="username" autocomplete="off" placeholder="Enter Username" maxlength="32" class="text_input" value="<?php echo esc_html( $whatso_username ); ?>" />
                        <lable class="error" id="username_error"><?php esc_html_e( 'Please copy API username properly from website.', 'whatso' ); ?></lable>
                    </div>
					 <div  class="col-md-6">
                        <input type="text" id="password" name="password" autocomplete="off" maxlength="32" placeholder="Enter Password" class="text_input"  value="<?php echo esc_html( $whatso_password ); ?>" />
                        <lable class="error" id="password_error"><?php esc_html_e( 'Please copy API password properly from website.', 'whatso' ); ?></lable>
                    </div>
                     
                <div class="input" style="display: flex;">
                   
                    <div class="username_info">
                        <a href="https://www.whatso.net/whatsapp-api" target="_blank"><?php esc_html_e( 'Get Username & Password', 'whatso' ); ?></a>
                    </div>
                </div>
                    
                </div>
               
                <div class="lable" style="margin-top:15px;">
                    <label><?php esc_html_e( 'WhatsApp Numbers With Country Code (You will receive notifications on these numbers)', 'whatso' ); ?></label><span class="required_star">*</span>
                </div>
                <div class="input" style="display: flex;">
                    <div>
                        <textarea type="text" name="mobileno" id="mobileno" autocomplete="off" maxlength="200" placeholder="Enter Mobile Number with country code. Do not prefix with a 0 or +" class="message1" ><?php echo esc_html( $whatso_mobileno ); ?></textarea>
                        <lable class="error" id="mobile_error"><?php esc_html_e( 'Please enter 12 digit number.', 'whatso' ); ?></lable>
                        <lable class="error" id="mobile_number_error"><?php esc_html_e( 'Please Enter only Number.', 'whatso' ); ?></lable>
                    </div>
                    <div class="username_info">
                        <label style="font-weight: bold !important;">(i)</label>
                    </div>
                    <div class="usernameinfo_box">
                        <p><?php esc_html_e( 'Required :', 'whatso' ); ?></p>
                        <span><?php esc_html_e( ' - Minimum 1 mobile number is required. You can separate multiple numbers with a comma. You can add upto 10 numbers.No need to add + in front of mobile number', 'whatso' ); ?></span>
                    </div>
                </div>
                <p id="newnumber"></p>
                <div class="lable">
                    <label><?php esc_html_e( 'Message Text', 'whatso' ); ?></label><span class="required_star">*</span>
                </div>
                <div class="input" style="display: flex;">
                    <div>
                        <textarea class="message" name="message" id="message" autocomplete="off" maxlength="1500" placeholder="Enter message that you want to be sent when the order is placed."><?php echo esc_html( $whatso_message ); ?></textarea>
                        <lable class="error" id="message_error"><?php esc_html_e( 'Your message must be atleast 2 characters.', 'whatso' ); ?></lable>
                    </div>
                    <div class="username_info">
                        <p>(i)</p>
                    </div>
                    <div class="usernameinfo_box">
                        <p><?php esc_html_e( 'Required :', 'whatso' ); ?></p>
                        <span><?php esc_html_e( ' - Message must be atleast 2 characters.', 'whatso' ); ?></span>
                    </div>
                </div>
				<div class="lable" style="margin-top:15px;">
                    <label><?php esc_html_e( 'Use below placeholder fields to dynamically add your order details in the WhatsApp message', 'whatso' ); ?></label>
                </div>
                <div class="placeholder_panal">
                    <button type="button" class="placeholder_button" onclick="add_placeholder( 'message', '{storename}' )"><?php esc_html_e( 'Store Name', 'whatso' ); ?></button>
                    <button type="button" class="placeholder_button" onclick="add_placeholder( 'message', '{orderdate}' )"><?php esc_html_e( 'Order Date', 'whatso' ); ?></button>
                    <button type="button" class="placeholder_button" onclick="add_placeholder( 'message', '{productname}' )"><?php esc_html_e( 'Product Name', 'whatso' ); ?></button>
                    <button type="button" class="placeholder_button" onclick="add_placeholder( 'message', '{amountwithcurrency}' )"><?php esc_html_e( 'Amount With Currency', 'whatso' ); ?></button><br/>
                    <button type="button" class="placeholder_button" onclick="add_placeholder( 'message', '{customeremail}' )"><?php esc_html_e( 'Customer Email', 'whatso' ); ?></button>
                    <button type="button" class="placeholder_button" onclick="add_placeholder( 'message', '{customernumber}' )"><?php esc_html_e( 'Customer Number', 'whatso' ); ?></button>
                    <button type="button" class="placeholder_button" onclick="add_placeholder( 'message', '{billingcity}' )"><?php esc_html_e( 'Billing City', 'whatso' ); ?></button>
                    <button type="button" class="placeholder_button" onclick="add_placeholder( 'message', '{billingstate}' )"><?php esc_html_e( 'Billing State', 'whatso' ); ?></button>
                    <button type="button" class="placeholder_button" onclick="add_placeholder( 'message', '{billingcountry}' )"><?php esc_html_e( 'Billing Country', 'whatso' ); ?></button>
                </div>
                <div class="submit">
                    <input type="submit" class="submit_button" name="notification_submit" value="Submit" />
                </div>
                <p class="note"><?php esc_html_e( 'Note:', 'whatso' ); ?></p>
                <ol class="note">
                    <li>This form helps you to setup configuration for sending a WhatsApp message to the website-owner / adminstrator.</li>
                    <li>Visit <a target="_blank" href="https://www.whatso.net/whatsapp-api">Whatso Website</a> to create a free account and get your username and password.</li>
                    <li>In the mobile number field, you need to enter the number of the web-administrator or the founder of the store. You can add upto 10 numbers separated by a comma.</li>
                    <li>The message field contains the message that will be sent when an order is successfully placed. You can use the placeholder keywords to set dynamic message content. You can use the below message also and copy-paste it in the message box above:
                        <br/><br/>
                        Hi, <br/>an order is placed on {storename} at Date: {orderdate}. Order is for {productname} and order amount is {amountwithcurrency}. Customer email is {customeremail}.
                    </li>
                </ol>
            </form> 
        </div>
        <div class="left"></div>
    </div>
</div>
<script>
let username = document.querySelector('#username');
let password = document.querySelector('#password');
let mobileno = document.querySelector('#mobileno');

username.onkeyup = function () {
    if (this.value.length < 32 || this.value.length > 32) {
        document.getElementById('username_error').style.display = 'block';
        return false;
    }else{
        document.getElementById('username_error').style.display = 'none';
        return true;
    }
}
password.onkeyup = function () {
    // alert('hello');
    if (this.value.length < 32 || this.value.length > 32) {
        document.getElementById('password_error').style.display = 'block';
        return false;
    }else{
        document.getElementById('password_error').style.display = 'none';
        return true;
    }
}
function isNumber(evt) {

var theEvent = evt || window.event;

    // Handle paste
    if (theEvent.type === 'paste') {
        //key = event.clipboardData.getData('text/plain');
        theEvent.returnValue = false;
    } else {
    // Handle key press
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
    }
    var regex = /[0-9]/;
    if( !regex.test(key) ) {
        theEvent.returnValue = false;
        if(theEvent.preventDefault) theEvent.preventDefault();
        document.getElementById('mobile_number_error').style.display = 'block';
        return false
    }else{
        document.getElementById('mobile_number_error').style.display = 'none';
        return true;
    }   
        
}
mobileno.onkeyup = function () {
    if(this.value.length < 12 || this.value.length > 159){
        document.getElementById('mobile_error').style.display = 'block';
        return false;
    }else {
        // document.getElementById('mobile_number_error').style.display = 'none';
        document.getElementById('mobile_error').style.display = 'none';
        return true;
    }
}

message.onkeyup = function(){
    if(this.value.length < 2 || this.value.length > 1500){
        document.getElementById('message_error').style.display = 'block';
        return false;
    }else {
        // document.getElementById('mobile_number_error').style.display = 'none';
        document.getElementById('message_error').style.display = 'none';
        return true;
    }
}


/**
 * Add placeholder on text area
 */

function getInputSelection(el) {
    var start = 0, end = 0, normalizedValue, range, textInputRange, len, endRange;
    if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
        start = el.selectionStart;
        end = el.selectionEnd;
    } else {
        range = document.selection.createRange();

        if (range && range.parentElement() == el) {
            len = el.value.length;
            normalizedValue = el.value.replace(/\r\n/g, "\n");

            // Create a working TextRange that lives only in the input
            textInputRange = el.createTextRange();
            textInputRange.moveToBookmark(range.getBookmark());

            // Check if the start and end of the selection are at the very end
            // of the input, since moveStart/moveEnd doesn't return what we want
            // in those cases
            endRange = el.createTextRange();
            endRange.collapse(false);

            if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                start = end = len;
            } else {
                start = -textInputRange.moveStart("character", -len);
                start += normalizedValue.slice(0, start).split("\n").length - 1;

                if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                    end = len;
                } else {
                    end = -textInputRange.moveEnd("character", -len);
                    end += normalizedValue.slice(0, end).split("\n").length - 1;
                }
            }
        }
    }
    return {
        start: start,
        end: end
    };
}

function offsetToRangeCharacterMove(el, offset) {
    return offset - (el.value.slice(0, offset).split("\r\n").length - 1);
}

function setSelection(el, start, end) {
    if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
        el.selectionStart = start;
        el.selectionEnd = end;
    } else if (typeof el.createTextRange != "undefined") {
        var range = el.createTextRange();
        var startCharMove = offsetToRangeCharacterMove(el, start);
        range.collapse(true);
        if (start == end) {
            range.move("character", startCharMove);
        } else {
            range.moveEnd("character", offsetToRangeCharacterMove(el, end));
            range.moveStart("character", startCharMove);
        }
        range.select();
    }
}
function insertTextAtCaret(el, text) {
    var pos = getInputSelection(el).end;
    var newPos = pos + text.length;
    var val = el.value;
    el.value = val.slice(0, pos) + text + val.slice(pos);
    setSelection(el, newPos, newPos);
}

function add_placeholder( text_area_id, placeholder ) {
    var textarea = document.getElementById( text_area_id );
    textarea.focus();
    insertTextAtCaret(textarea, placeholder);
    return false;
}
</script>