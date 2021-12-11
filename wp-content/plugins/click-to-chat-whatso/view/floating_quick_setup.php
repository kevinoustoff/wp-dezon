<?php
$box_position = '' === WHATSO_Utils::getSetting( 'box_position' ) ? 'right' : WHATSO_Utils::getSetting( 'box_position' );
$availability = array(
  'sunday' => array(
    'hour_start' => 0,
    'minute_start' => 0,
    'hour_end' => 23,
    'minute_end' => 59
  )
  ,
  'monday' => array(
    'hour_start' => 0,
    'minute_start' => 0,
    'hour_end' => 23,
    'minute_end' => 59
  )
  ,
  'tuesday' => array(
    'hour_start' => 0,
    'minute_start' => 0,
    'hour_end' => 23,
    'minute_end' => 59
  )
  ,
    'wednesday' => array(
    'hour_start' => 0,
    'minute_start' => 0,
    'hour_end' => 23,
    'minute_end' => 59
  )
  ,
  'thursday' => array(
    'hour_start' => 0,
    'minute_start' => 0,
    'hour_end' => 23,
    'minute_end' => 59
  )
  ,
  'friday' => array(
    'hour_start' => 0,
    'minute_start' => 0,
    'hour_end' => 23,
    'minute_end' => 59
  )
  ,
  'saturday' => array(
    'hour_start' => 0,
    'minute_start' => 0,
    'hour_end' => 23,
    'minute_end' => 59
  )
);

static $stateOptionName = WHATSO_SETTINGS_NAME;
$option = get_option( self::$stateOptionName );
$data = json_decode( $option, true );
//$selected_data= $data['selected_accounts_for_widget'];


/**
    code for quick setup
 */?>
 <!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="http://fonts.cdnfonts.com/css/segoe-ui-4" rel="stylesheet">   
    <title>Quick Setup</title>
    <style>
      body{
        margin-top:30px;
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;
	
      }
       .main-box{
                margin:0px auto;
                /* width:50%; */
                /* border:1px solid; */
                /* z-index: 100; */
                color: #fff;
				display:flex;
                border-radius: 10px;
                box-shadow: 0px 15px 50px 10px rgb(0 0 0 / 40%);
                /* box-sizing: border-box; */
                width: 800px;
				overflow:hidden;
                background: #fff;
                /*padding: 40px 25px;*/
            }
            .head{text-align:center;}
            .head-title{
                font-weight: bolder;
                margin-block-start: 0px;
                margin-block-end: 0px;
                font-size: 2.5rem;
                letter-spacing: 0px;
                letter-spacing: 0.05rem;
                color: #1ea185;
            }
            .head-subtitle{
                font-weight: 100;
                font-size: 1.5rem;
                letter-spacing: 0.05rem;
                color: #9bbb5c;
                margin-block-start:0px;
                margin-top:5px;
            }
            .form-body{
                margin:0px auto;
                overflow: hidden;
					line-height:1.7;
            }
            .mta{
                color: rgba(0, 0, 0, 0.61);
                font-size: 18px;
            }
            .full-width {
                width: 95%;
                
            }
            .input-line {
                color: black;
                font-family: roboto;
                font-weight: 300;
                padding : 10px 5px;
                font-size: 1.2rem;
            }
            .col_widget{
                margin: 1px 0px;
            }
            .target_widget{
                margin: 10px 0px;
            }
            .firstbutton {
                width: 30%;
                padding: 8px 0px;
                border-radius: 50px;
                margin: 0 auto;
                background-color: #485566;
                color: #fff;
                font-weight: 500;
                font-size: medium;
            }
            .submit_button{
                margin-top:20px;
                text-align:center;
            }
            .message{
                text-align:center;
                width:100%;
            }
            .message_text{
                color:red;
                font-size:10px;
                font-display: block;
                
            }
            .check_style{
                margin: 0px 10px;
            }
	      		#errormsg{
              color:red;
              margin-left: 5px;
              font-size:15px!important
            }
            #phonemsg{
              color:red;
              margin-left: 5px;
              font-size:15px!important
          
            }
            #msg3{
                   color:red;
              margin-left: 5px;
              font-size:15px!important
            }
            /* #home{
                margin-top:10px;
            } */
			label{
				cursor: default !important;
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
			.center{
				width: 100%;
                margin: 0px auto;
                padding: 30px 50px;
			}
			.check_label{
				color:black;
				font-size: 16px;
			}
			.check_box{
				margin-top:5px;
			}
			.toggle_background_color{
				width:15%;
				height:30px;
			}
				
    </style>
    
</head>

 
        
<!--html form for quicksetup-->
<body>
<div class="main-box">
<div class="left"></div>
<div class="center">
	<form  method="post" name="form1">
            <div class="head">
                <h2 class="head-title">Quick Setup</h2>
                <p class="head-subtitle">Click to Chat</p>
            </div>
            
            <div class="form-body">
				<div style="clear:both;">
					<div style="float:left;width:50%;margin:10px 0px;">
						<label for="" class="mta">Your name: </label><br/> 
						<input type="hidden" name="whatso_name"  value="whatso_name"> 
						<input type="text" onkeypress="return blockSpecialChar(event)" onClick="this.setSelectionRange(0, this.value.length)" name="whatso_name1" id="whatso_name1" placeholder='Your name' autocomplete="off" class='input-line full-width' onpaste="return false"  maxlength="50" required>
						<span id="errormsg"></span><br/>
					</div>
					<div style="float:left;width:50%;margin:10px 0px;">
						<label for="" class="mta">Mobile number: </label> 
						<input type="hidden" name="whatso_number" value="whatso_number"> 
						<input type="text" name="whatso_number1" id="whatso_number1" onClick="this.setSelectionRange(0, this.value.length)" placeholder=' Mobile number with country code' autocomplete="off" class='input-line full-width'  maxlength="15"  onpaste="return false" onkeypress="return isNumber(event)"  required>
						<span id="phonemsg"></span><br/>
					</div>
				</div>
				
                
                

                <input type="hidden" name="whatso_title" id="whatso_title" value="whatso_title">
                
                <input type="hidden" name="whatso_predefined_text" id="whatso_predefined_text" value="whatso_predefined_text">
                
                <input type="hidden" name="whatso_button_label" id="whatso_button_label" value="whatso_button_label">
                
                <input type="hidden" name="whatso_offline_text" id="whatso_offline_text" value="whatso_offline_text">
                
                <input type="hidden" name="whatso_hide_on_large_screen" id="whatso_hide_on_large_screen" value="whatso_hide_on_large_screen">
                
                <input type="hidden" name="whatso_hide_on_small_screen" id="whatso_hide_on_small_screen" value="whatso_hide_on_small_screen">
                
                <input type="hidden" name="whatso_pin_account" id="whatso_pin_account" value="whatso_pin_account">
                
                <input type="hidden" name="whatso_background_color_on_hover" id="whatso_background_color_on_hover" value="whatso_background_color_on_hover">
                
                <input type="hidden" name="whatso_text_color" id="whatso_text_color" value="whatso_text_color">
                
                <input type="hidden" name="whatso_text_color_on_hover" id="whatso_text_color_on_hover" value="whatso_text_color_on_hover">
                
                <input type="hidden" name="whatso_included_ids" id="whatso_included_ids" value="whatso_included_ids">
                
                <input type="hidden" name="whatso_excluded_ids" id="whatso_excluded_ids" value="whatso_excluded_ids">
                
                <input type="hidden" name="whatso_target_languages" id="whatso_target_languages" value="whatso_target_languages">
				
				<div style="clear:both;">
					<div style="float:left;width:50%;margin:10px 0px;">
						<label for="" class="mta ">Widget position:</label> 
						<select id="box_position" name="box_position" class='input-line full-width' style="font-size:18px;font-family:roboto">
						<option value="left" name="box_position" id="box_position_left" <?php echo esc_html('left') === esc_attr($box_position) ? 'selected' : ''; ?> class='input-line full-width' ><label for="box_position_left"><?php esc_html_e( 'Bottom Left', 'whatso' ); ?></label></option>
						<option value="right" name="box_position"  id="box_position_right" <?php echo esc_html('right') === esc_attr($box_position) ? 'selected' : ''; ?> class='input-line full-width'><label for="box_position_right"><?php esc_html_e( 'Bottom Right', 'whatso' ); ?></label></option>
						</select>
						<div style="margin:9px 0px 0px 0px;">
							<label for="" class="mta">Widget background color:</label>
							<input type="hidden" name="whatso_background_color" id="whatso_background_color" value="whatso_background_color"><br>
							<input type="color" name="toggle_background_color" id="toggle_background_color" class="toggle_background_color" value="#04e474" />
						</div>
					</div>
				</div>
				
				<div style="margin:0px auto;">
					<div style="margin-top:15px;">
						<label class="mta" >Show Click-to-Chat Widget on: </label>
						<input type="hidden" name="whatso_target" id="whatso_target" value="whatso_target">
					</div>
					<div style="display:flex;">
						<div style="margin:10px 0px;">
							<div class="check_style">
								<input type="checkbox" name="whatso_target1[]" class="check_box" value="home" id="home" checked><label for="home" class="check_label">Homepage</label> <br>    
							</div>
							<div class="check_style">
								<input type="checkbox" name="whatso_target1[]" class="check_box" value="blog" id="blog" checked><label for="home" class="check_label">Blog index</label><br>      
							</div>
							<div class="check_style">
								<input type="checkbox" name="whatso_target1[]" class="check_box" value="archive" id="archive" checked><label for="home" class="check_label">Archives</label><br> 
							</div>
						</div>
						<div style="margin:10px 0px;">
							<div class="check_style">
								<input type="checkbox" name="whatso_target1[]" class="check_box" value="page" id="page" checked><label for="home" class="check_label">Pages</label><br>      
							</div>
							<div class="check_style">
								<input type="checkbox" name="whatso_target1[]" class="check_box" value="post" id="post" checked><label for="home" class="check_label">Blog Posts</label><br> 
							</div>
						</div>
					</div>
					<div style="text-align:center;">
						<span id="msg3"></span>
					</div>
				</div>
               
                <input type="hidden" name="whatso_availability" id="whatso_availability" value="whatso_availability"> 
                <div class="submit_button">
                    <button type="button" class="firstbutton" onclick="FormValidation()">Create Account</button>
                </div>
              
            </div>
  <?php 
        
  if (!empty($_POST)) {

    $whatso_name1 = isset( $_POST['whatso_name1'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_name1'] ) ) : '';  
    $whatso_number1 = isset( $_POST['whatso_number1'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_number1'] ) ) : ''; 
    $whatso_title = isset( $_POST['whatso_title'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_title'] ) ) : '';  
    $whatso_predefined_text = isset( $_POST['whatso_predefined_text'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_predefined_text'] ) ) : '';  
    $whatso_button_label = isset( $_POST['whatso_button_label'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_button_label'] ) ) : '';    
    $whatso_offline_text = isset( $_POST['whatso_offline_text'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_offline_text'] ) ) : '';
    $whatso_hide_on_large_screen = isset( $_POST['whatso_hide_on_large_screen'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_hide_on_large_screen'] ) ) : '';
    $whatso_hide_on_small_screen = isset( $_POST['whatso_hide_on_small_screen'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_hide_on_small_screen'] ) ) : '';
    $whatso_pin_account = isset( $_POST['whatso_pin_account'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_pin_account'] ) ) : '';
    $whatso_background_color_on_hover = isset( $_POST['whatso_background_color_on_hover'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_background_color_on_hover'] ) ) : '';
    $whatso_text_color = isset( $_POST['whatso_text_color'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_text_color'] ) ) : '';
    $whatso_text_color_on_hover = isset( $_POST['whatso_text_color_on_hover'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_text_color_on_hover'] ) ) : '';
    $whatso_included_ids = isset( $_POST['whatso_included_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_included_ids'] ) ) : '';
    $whatso_excluded_ids = isset( $_POST['whatso_excluded_ids'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_excluded_ids'] ) ) : '';
    $whatso_target_languages = isset( $_POST['whatso_target_languages'] ) ? sanitize_text_field( wp_unslash( $_POST['whatso_target_languages'] ) ) : '';
    $box_position = isset( $_POST['box_position'] ) ? sanitize_text_field( wp_unslash( $_POST['box_position'] ) ) : '';
    $toggle_background_color = isset( $_POST['toggle_background_color'] ) ? sanitize_text_field( wp_unslash( $_POST['toggle_background_color'] ) ) : '';
    
    global $wpdb;
        $table = $wpdb->prefix . "postmeta";
        $table1 = $wpdb->prefix . "posts";
        $table2= $wpdb->prefix . "options";
        $date=date('y.m.d h:i:s');
        
        $post_data = array(
            'ID' => "",
            'post_author'=>"1",
            'post_date'=>$date,
            'post_date_gmt'=>$date,
            'post_title'    => $_POST['whatso_name1'],
            'comment_status'=>"closed",
            'ping_status'=>"closed",
            'post_name'=> $_POST['whatso_name1'],
            'post_type' => "whatso_accounts"

        );
        $format = array(
            '%s',
            '%s',

          
        );
        $success=$wpdb->insert( $table1, $post_data, $format );
        $post_title= $whatso_name1;
        $post_id    = $wpdb->get_results( " SELECT ID  FROM $wpdb->posts WHERE post_title = '$post_title' ");
        $array = json_decode(json_encode($post_id),true);
        foreach($array as $arr2){
            foreach($arr2 as $id=>$p_id){
                $p_id;
            }
        }
       
        $guid = get_permalink($p_id);
        $wpdb->update( 
          $table1, 
          array( 
              'guid' => $guid,
          ), 
          array( 'ID' => $p_id )
        );
       
        $data = array(
            'post_id' => $p_id,
            'meta_key' => $_POST['whatso_name'],
            'meta_value'    => $whatso_name1,

        );
        $success=$wpdb->insert( $table, $data, $format );
        
        $number = $whatso_number1;
        $num=substr($number,0,1);
        if($num != "+")
        {
        $num2 = "+";
        $number=$num2.$number;
        }
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $_POST['whatso_number'],
            'meta_value'    => $number,

        );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_title,
            'meta_value'    => '',

        );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_predefined_text,
            'meta_value'    => 'Hi!',

        );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_button_label,
            'meta_value'    => '',

        );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_offline_text,
            'meta_value'    => '',
    
          );
        $success=$wpdb->insert( $table, $data1, $format );
      
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_hide_on_large_screen,
            'meta_value'    => 'off',
  
          );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_hide_on_small_screen,
            'meta_value'    => 'off',
  
          );
        $success=$wpdb->insert( $table, $data1, $format ); 
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_pin_account,
            'meta_value'    => 'off',
  
          );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
          'post_id' => $p_id,
          'meta_key' => $_POST['whatso_background_color'],
          'meta_value'    =>  $toggle_background_color,

        );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_background_color_on_hover,
            'meta_value'    => '',
  
          );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_text_color,
            'meta_value'    => '',
  
          );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_text_color_on_hover,
            'meta_value'    => '',
  
          );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_included_ids,
            'meta_value'    => '[]',
  
          );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_excluded_ids,
            'meta_value'    => '[]',
  
          );
        $success=$wpdb->insert( $table, $data1, $format );
        
        $data1 = array(
            'post_id' => $p_id,
            'meta_key' => $whatso_target_languages,
            'meta_value'    => '[]',
    
          );
        $success=$wpdb->insert( $table, $data1, $format );
        
        if (isset($_POST['whatso_target1'])) {
          foreach ($_POST['whatso_target1'] as $page) {
              $pages = " " . $page;
              $t[] = sanitize_text_field( $pages );
          }
        }else{
          $pages= [];
        }  
        
        $data1 = array(
        'post_id' => $p_id,
        'meta_key' => $_POST['whatso_target'],
        'meta_value'    => wp_json_encode( $t ),
          );
        $success=$wpdb->insert( $table, $data1, $format );
       
        $data1 = array(
        'post_id' => $p_id,
        'meta_key' => $_POST['whatso_availability'],
        'meta_value'    => wp_json_encode( $availability),

        );
        $success=$wpdb->insert( $table, $data1, $format );
    
        WHATSO_Utils::updateSetting( 'box_position', $box_position );
        $category='selected_accounts_for_widget';
        $selected_accounts= json_decode( WHATSO_Utils::getSetting( $category, '' ), true );
          
      if($selected_accounts == [])
      {
      
        $p_id = is_array( $p_id ) ? $p_id : array($p_id,0,0);
		    //$p_id = count( $p_id ) < 1? array( 0 ) : $p_id;
        WHATSO_Utils::updateSetting( 'selected_accounts_for_widget', wp_json_encode( $p_id) ); 

      }
      else{

        array_push($selected_accounts,$p_id); 
        WHATSO_Utils::updateSetting( 'selected_accounts_for_widget', wp_json_encode( $selected_accounts) ); 

      } 
        WHATSO_Utils::updateSetting( 'toggle_background_color', $toggle_background_color );
        WHATSO_Utils::generateCustomCSS();          
        
    if($success){
                  echo '<center><p class="mta" style="visibility:visible;"><font color="green" >Account created successfully. You can check widget on website. Click here for more &nbsp;<a href="edit.php?post_type=whatso_accounts">Settings</a>.</p></font>' ; 
    }else{
                  echo '<p>Please try again!</p>' ; 
          }  
  }
          

?>
        
</form>
</div>
<div class="left"></div>
</div>
<script>
function blockSpecialChar(e){
        var k;
        document.all ? k = e.keyCode : k = e.which;
        //alert(k); //39 == '
        return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8 || k == 32 || (k >= 48 && k <= 57)||k == 39);
        
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
        }

}
 function FormValidation()
  {
        var txtname = document.getElementById("whatso_name1").value;
        var txtmobile = document.getElementById("whatso_number1").value;
        var phoneno = /^[0-9]*$/;
        //Check Name
        if(txtname.length < 2 ||txtname.length >50 )
        {
          document.getElementById('errormsg').innerHTML  = "Name must be atleast 2 characters";
          return false;
        }else{
			document.getElementById('errormsg').innerHTML  = "";
		}
        

        //Check Mobile
        if(txtmobile.length < 5 ||txtmobile.length >15 )
        {
          document.getElementById('phonemsg').innerHTML  = "Number must be atleast 5 digits";
          return false;
        }else{
			document.getElementById('errormsg').innerHTML  = "";
		}

        // Check Checkbox
        if(!document.getElementById('home').checked && !document.getElementById('blog').checked && !document.getElementById('archive').checked && !document.getElementById('page').checked && !document.getElementById('post').checked)
        {
          document.getElementById('msg3').innerHTML  = "Please select atleast one checkbox";
          return false;

        }else{
			document.getElementById('errormsg').innerHTML  = "";
		}
        //Submit
        document.forms["form1"].submit();

  }
  
  
</script>
 </body>


