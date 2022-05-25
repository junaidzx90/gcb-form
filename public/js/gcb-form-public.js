jQuery(function ($) {
  'use strict';

  function formatBytes(bytes, decimals = 2) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
  }

  let insertImage = function (input) {
    if (input.files && input.files[0]) {
      let reader = new FileReader();
      reader.onload = function (e) {
        $('.profile_image_upload img').attr('src', e.target.result);
      };
      reader.readAsDataURL(input.files[0]);
    }
  };

  $('#gcb_reg_profile_image').on('change', function () {
    if ($(this).val() !== '') {
      let imgName = $(this)
        .val()
        .replace(/.*(\/|\\)/, '');
      let exten = imgName.substring(imgName.lastIndexOf('.') + 1);
      let expects = ['jpg', 'jpeg', 'png', 'PNG', 'JPG', 'gif'];

      if (expects.indexOf(exten) == -1) {
        $('.profile_image_upload img').attr('src', '');
        alert('Invalid Image!');
        return false;
      }

      if ($(this)[0].files[0].size > ajax_data.max_upload) {
        alert(
          'You can upload maximum ' + formatBytes(ajax_data.max_upload) + '!'
        );
        return false;
      }

      insertImage(this);
    } else {
      $('.profile_image_upload img').attr('src', '');
    }
  });

  // Form validation
  // $("#gcb__registration_form").on("submit", function(e){
  //     $(this).find("input[type='text'], input[type='number'], input[type='email'], select").each(function () {
  //         if($(this).val().length === 0 || $(this).val() === '-1'){
  //             e.preventDefault();
  //             $(this).css("border-color", "red");
  //         }

  //         $(this).on("keyup, change", function(){
  //             if($(this).val().length > 0 || $(this).val() !== '-1'){
  //                 $(this).css("border-color", "#00d4c6");
  //             }
  //         })
  //     });
  // });

  $('input[name="gcb_reg_pubgmid"]').on('keyup', function () {
    if ($(this).val().length < 7 || $(this).val().length > 15) {
      this.setCustomValidity(
        'PUBGMID Should be maximum 7 characters length upto 15 characters!'
      );
    } else {
      this.setCustomValidity('');
    }
  });

  // Form validation
  $('#gcb__login_form').on('submit', function (e) {
    $(this)
      .find("input[type='text'], input[type='password']")
      .each(function () {
        if ($(this).val().length === 0) {
          e.preventDefault();
          $(this).css('border-color', 'red');
        }

        $(this).on('keyup, change', function () {
          if ($(this).val().length > 0) {
            $(this).css('border-color', '#00d4c6');
          }
        });
      });
  });
});
