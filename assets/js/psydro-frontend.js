jQuery(document).ready(function($){
    jQuery('body').addClass('psydro-plugin');
 
    var write_review_btn = $('.psydro-review-btn'),
        psydro_images = [],
        sign_up_countdown_elem =  $('#psydro-sign-up-otp-countdown')

    manage_signup_otp_countdown();

    $(window).load(function() {
       $('.psydro-loader-box').remove();
    });

      $('[data-fancybox="psydro"]').fancybox({
        'zoomSpeedIn': 300,
        'zoomSpeedOut': 300,
        'overlayShow': false,
        'enableEscapeButton': true,
        'showCloseButton': true,
      }); 


    $("#psydro_phone").keypress(function (evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
        }
        return true;
    });

    $("#psydro_otp").keydown(function (evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
        }
        return true;
    });

    function validateEmail(signupemail) {
      var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(signupemail);
    }

    $(document).on('click', '.psydro-read-more-point', function() {
          var review_id = $(this).data('review-id'),
          content = '',
          review_username =  $('#'+review_id).find('.psydro-review-username').text(),
          review_time =  $('#'+review_id ).find('.psydro-rating-time').text(),
          rating =  $('#'+review_id).find('.psydro-vertical-slider-stars').html(),
          review_title =  $('#'+review_id).find('.psydro-rating-header-text').html(),
          review = $('#'+review_id).find('.psydro-review-full').html()

  
          content +=   '<div><span class="psydro-review-view-more-username">' +review_username + '</span> <span class="psydro-review-view-more-datetime">' + review_time +'</div>';
          content +=   rating
          content +=  '<div class="psydro-review-view-more-title">'+review_title+'</div>';
          content +=   '<div class="psydro-review-view-more-review">'+review+'</div>';

          $('#psydro-review-read-more-content').html(content);
          fire_psydro_modal('#psydro-review-read-more-popup');
    });

    $('.psydro-review-btn').click(function( ) {
        write_review_btn.text( has_signup_otp() ? 'Enter OTP' : 'Write A Review' );  
        if( get_psydro_cookie('sign_up_datetime')) {
          fire_psydro_modal('#psydro-sign-up-otp-popup')
        }else if( get_psydro_cookie( 'user_token')) {
          fire_psydro_modal('#psydro-write-review-popup')            
        }else{
          fire_psydro_modal('#psydro-sign-in-popup')
        }
    })

    $(document).on('click', '.psydro-fancybox', function() {
        fire_psydro_modal($(this).attr('href'));
    })

    $(document).on('click', '.psydro-fancybox-close', function() {
      jQuery.fancybox.close();
    })

    $(document).on('click', '.psydro-resend-otp', function() {
        var form = $('#psydro-otp');
        start_form_submit(form)
        handle_psydro_request({
            url: 'otpresend',
            method: 'post',
            data: { }
          }).then(function(response) {
            handle_ajax_response(response.data, form )
            if(response.data.error === false) {
              set_psydro_cookie('sign_up_datetime', new Date())
              manage_signup_otp_countdown()
            }
            stop_form_submit(form)
          }, error => { stop_form_submit(form) })
          return false;
    })

   

    function fire_psydro_modal(src) {
      parent.jQuery.fancybox.close();
      var psydro_fancybox = jQuery.fancybox.open({ src: src });

      psydro_fancybox.current.opts.afterClose = function ( ) {

        if($(src + '  form').length > 0 ){
          $(src + '  form')[0].reset();
        }
        
        $(document).find('.psydro-remove').trigger('click');
      }
    }

      var obj = $('.psydro-inverified-multiple-upload').psydroReviewImageUpload({
          thumbWrapWithIn: '.psydro-inverified-multiple-upload-thumbs',
          multipleFiles: true,
          limit: 3,
          uploadOnSelect: true,
          ajaxUrl: "https://www.psydro.com/api/upload-temp-image",
          disabledBtnClass: '#hb-submit-writeRev'
        }, function( blob ) {
           
        } );

    function has_signup_otp() {
      return get_psydro_cookie('sign_up_datetime')
    }

    function manage_signup_otp_countdown( ) {
        write_review_btn.text( has_signup_otp() ? 'Enter OTP' : 'Write A Review' );  
        var sign_up_datetime = get_psydro_cookie('sign_up_datetime');
            sign_up_datetime = moment(sign_up_datetime).add(30, 'seconds');

        var counter = setInterval(function(){
          seconds = moment().diff(sign_up_datetime, 'seconds');        
          if(seconds > 0 || isNaN(seconds)) {
            clearTimeout(counter)
            sign_up_countdown_elem.html('<div id="psydro-resend-text"><a class="psydro-resend-otp" href="javascript:void(0)">Resend OTP </a></div>');
          }else{
            sign_up_countdown_elem.html('<div> Resend OTP in <span id="time">'+ Math.abs(seconds) +'</span> seconds!</div>');
          }
        }, 1000);
    }

    function get_psydro_input_value(name, form) {
      return $(form).find('input[name="'+name+'"]').val();
    }

    function handle_psydro_request(request) {
      request.headers = get_psydro_header();
      request.url = psydro_env.base_url + request.url

        if( request.data instanceof FormData){
          request.data.append('api_token', get_psydro_cookie('sign_up_token') ? get_psydro_cookie('sign_up_token') : get_psydro_cookie('user_token'));
      
         }else{
           request.data.api_token = get_psydro_cookie('sign_up_token') ? get_psydro_cookie('sign_up_token') : get_psydro_cookie('user_token');
         }   
        return axios(request);
    }

    function get_psydro_header() {
      return { 'ApiKey': 'fqi0PE~ao8RA&Ijk2u71FcJl}kQF/1'};
    }

    function handle_ajax_response(response, form) {
      var message_element =  $(form).find('.psydro-message-container'),
          message_class = response.error === true ? 'error' : 'success'

      $(message_element).html('')
      $(message_element).html('<div class="psydro-message psydro-'+message_class+'">'+ response.message +'</div>');
      
      clearTimeout(message_timeout);

      var message_timeout = setTimeout(function() {
        $(message_element).html('')
      }, 600000)
    }

    function start_form_submit(form) {
      $(form).find('input[type="submit"]').attr('disabled', true)
    }

    function stop_form_submit(form) {
       $(form).find('input[type="submit"]').attr('disabled', false)
    }

    function generate_psydro_image_thumbnail(file,index) {
        if (file) {
          if( file.size > 5242880 ){
            alert('You selected image is more than 5 MB');
            return;
          }
        var reader = new FileReader();
        reader.onload = function(e) {
          $('#psydro_image_upload_box').append('<div class="psydro_review_thumbnail" id="psydro_review_thumbnail_'+index+'"  > <img  src="' + e.target.result+'"/> <span class="remove_psydro_thumbnail"  data-id='+index+'> x </span> </div>');
        }
        reader.readAsDataURL(file);
      }
    }

    function get_psydro_cookie(cname) {
        var name = 'psydro_'+cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    
    function set_psydro_cookie(key, value) {
        var date = new Date();
        date.setTime(date.getTime()+(10*60*1000));
        var expires = "; expires="+date.toGMTString();
        document.cookie = 'psydro_'+key +' ='+ value + expires + '; path=/';
    }
 
    function delete_psydro_cookie(name) {
        document.cookie = 'psydro_'+name + '=; Path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }

    function fancybox_close(){
      jQuery.fancybox.close();
    }

    $("#psydro-signin").validate({
      rules: {
        psydro_email: {
          required: true,
          email: true
        },
        psydro_password: {
          required: true
        },
      },
      messages: {
        psydro_email: {
          required: 'Email is required.',
          email: 'Email is not valid.'
        },
        psydro_password: {
          required: 'Password is required.',
        },
      },
      submitHandler : function(form) {
          start_form_submit(form)
          handle_psydro_request( {
            url: 'logInByExtension',
            method: 'post',
            data: {
              email: get_psydro_input_value('psydro_email', form),
              password: get_psydro_input_value('psydro_password', form),
              from: '1',
              user_type: '1',
              device_type: '2',
            }
          }).then(function(response) {
            stop_form_submit(form)
            handle_ajax_response(response.data, form)
            if(response.data.error === false) {
              $(form)[0].reset()
              //$( ".psydro-message-container" ).empty();
              set_psydro_cookie('user_token', response.data.api_token);
              
              setTimeout(function() {
                fire_psydro_modal('#psydro-write-review-popup'); 
                $( ".psydro-message-container" ).empty();
              }, 2000)
            } 
          },error => {
            stop_form_submit(form)
          })
          return false;
        }
    });

    $("#psydro-signup").validate({
      rules: {
        psydro_username: {
          required: true,
        },
        psydro_phone: {
          required: true,
        },
        psydro_signupemail: {
          required: true,
          emailfull: true
        },
        psydro_countryCode: {
          required: true,
        },
        psydro_terms_condition: {
          required: true,
        }
      },
      messages: {
        psydro_username: {
          required: 'Username is required.',
        },
        psydro_phone: {
          required: 'Phone No. is required.',
          minlength: 'Please enter at least 9 numbers.',
        },
        psydro_signupemail: {
          required: 'Email is required.',
        },
        psydro_countryCode: {
          required: 'Country code is required.',
        },
        psydro_terms_condition: {
          required: 'Accept terms and condition',
        }
      },
      showErrors: function(errorMap, errorList) {
          if(errorList.length > 0) {
              $("#psydromyErrorContainer").html('<p>'+errorList[0]['message']+'</p>');
          }else{
           $("#psydromyErrorContainer").html('');
          }
      },
      submitHandler : function(form) {
          start_form_submit(form)

          function check_Email(mail){
            var regex = /^(([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5}){1,25})+([;.](([a-zA-Z0-9_\-\.]+)@{[a-zA-Z0-9_\-\.]+0\.([a-zA-Z]{2,5}){1,25})+)*$/;
            if(regex.test(mail.signupemail.value)){
              return true;
              alert("Congrats! This is a valid Email email");
            }
            else{
              alert("This is not a valid email address");
              return false;
            }
          }

          var response =  handle_psydro_request( {
            url: 'signupByExtension',
            method: 'post',
            data: {
              username: get_psydro_input_value('psydro_username', form),
              phone: get_psydro_input_value('psydro_countryCode', form) + get_psydro_input_value('psydro_phone', form),
              email: get_psydro_input_value('psydro_signupemail', form),
            }
          });

          response.then(function(response) {
            stop_form_submit(form)
            handle_ajax_response(response.data, form)
            if(response.data.error === false) { 
                $(form)[0].reset()
                set_psydro_cookie( 'sign_up_token', response.data.api_token )
                
                setTimeout(function() {
                    set_psydro_cookie('sign_up_datetime', new Date())
                    fire_psydro_modal('#psydro-sign-up-otp-popup');
                    manage_signup_otp_countdown();
                    $( ".psydro-message-container" ).empty();
                }, 2000);
            }
          },error => {
            stop_form_submit(form)
          })

          return false;
        }
    });

    $("#psydro-sign-up-otp-validate").validate({
      rules: {
        psydro_otp: {
          required: true,
        },
      },
      messages: {
        psydro_otp: {
          required: 'OTP is required.',
        },
      },
      submitHandler : function(form) {
          start_form_submit(form)

          var response =  handle_psydro_request( {
            url: 'otpVerification',
            method: 'post',
            data: {
              otp: get_psydro_input_value('psydro_otp', form),
            }
          });

          response.then(function(response) {
            stop_form_submit(form)
            handle_ajax_response(response.data, form)
            if(response.data.error === false) {
              $(form)[0].reset()
              
              delete_psydro_cookie('sign_up_datetime');  
              set_psydro_cookie('user_token', get_psydro_cookie('sign_up_token') );
              delete_psydro_cookie('sign_up_token');  
              
              setTimeout(function() {
                manage_signup_otp_countdown(); 
                fire_psydro_modal('#psydro-write-review-popup')
                $( ".psydro-message-container" ).empty();
              }, 2000)
            }
          },error => {
            stop_form_submit(form)
          })

          return false;
        }
    });

    $("#psydro-forgot").validate({
      rules: {
        psydro_forgotemail: {
          required: true,
          email: true
        },
      },
      messages: {
        psydro_forgotemail: {
          required: 'Email is required.',
          email: 'Email is not valid.'
        },
      },
      submitHandler : function(form) {
          start_form_submit(form);

          handle_psydro_request( {
            url: 'ExtensionForgetPass',
            method: 'post',
            data: {
              email: get_psydro_input_value('psydro_forgotemail', form),
            }
          }).then(function(response) {
            stop_form_submit(form)
            handle_ajax_response(response.data, form)
          },error => { stop_form_submit(form) })
          return false;
        }
    });

    $(".psydro-star").click(function(){
        var starselect = $(this).attr("id");
        for( var j = 5 ; j>starselect ; j--)
        {
          $("#"+j).removeClass("starchecked");
        }
        for( var j = 1 ; j<= starselect ; j++)
        {
          $("#"+j).addClass("starchecked");
        }
        $(".psydro-rvw-star").attr("data-rating",starselect);
    });

    $("#psydro-write-review-form").validate({ 
      rules: {
        psydro_review_title: {
          required: true,
        },
        psydro_review_description: {
          required: true,
        },
      },
      messages: {
        psydro_review_title: {
          required: 'Title is required.',
        },
        psydro_review_description: {
          required: 'Description is required.',
        },
      },

      submitHandler : function(form) {
        var starselect = $(".psydro-rvw-star").data("rating");
        var star = starselect == undefined ? 5 : starselect;

        start_form_submit(form)
        var form_data = new FormData(),
          images = $("input[name='psydro-unverified-images[]']").map(function() {
            return $(this).val();
        }).get();
        
        form_data.append('site_token', psydro_env.api_key)
        form_data.append('title', get_psydro_input_value('psydro_review_title', form))
        form_data.append('description', jQuery('textarea[name=psydro_review_description]').val())
        form_data.append('rating', star)
        form_data.append('unverified_images', images);

        handle_psydro_request( {
          url: 'writeReviewByExtention',
          method: 'post',
          data: form_data,
        }).then(function(response) {
          stop_form_submit(form)
          handle_ajax_response(response.data, form)
          
          if(response.data.error === false) {
              $(form)[0].reset();
              fire_psydro_modal('#psydro-success-feedback-popup');
              delete_psydro_cookie('user_token');
              /*setTimeout(function() {
                fancybox_close();
                $( ".psydro-message-container" ).empty();
              }, 2000)*/
          }
        },error => { stop_form_submit(form) })
          return false;
        }
    });

    jQuery.validator.addMethod("emailfull", function(value, element) {
     return this.optional(element) || /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i.test(value);
    }, "Please enter valid email address!");

    jQuery("#psydro-images").change(function(e){
        var files = e.target.files;
        for (var i = 0; i < files.length ; i++) {
          if( files.length <= 3 ){
            if(psydro_images.length < 3) {
              psydro_images.push(files[i]);  
              generate_psydro_image_thumbnail(files[i],i)
            } 
            else {
               alert("You can upload maximum of 3 images");
               break;
             }
          }
          else{
            alert('You can upload maximum of 3 images end');
            break;
          }
        }   
    });

    $(document).on('click', '.remove_psydro_thumbnail', function(){
      var id = $(this).data('id');
      $('#psydro_review_thumbnail_'+id ).remove();
      psydro_images.pop(id);
    });

}(jQuery));
