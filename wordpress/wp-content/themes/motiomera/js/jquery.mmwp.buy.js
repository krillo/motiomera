jQuery(document).ready(function($) {
  var r3_price = $('#m_r3_price').val();
  var r4_price = $('#m_r4_price').val();
  var frakt00_price = $('#m_frakt00_price').val();
  var frakt00_extra = $('#m_frakt00_extra').val();
  var frakt01_price = $('#m_frakt01_price').val();
  var frakt01_extra = $('#m_frakt01_extra').val();
  var frakt02_price = $('#m_frakt02_price').val();
  var priv3_price = $('#m_priv3_price').val();
  var priv12_price = $('#m_priv12_price').val();
  var moms_percent = $('#m_moms_percent').val();
  

    //is there a hash
    var hash1 = location.hash;
    hashHandler(hash1);

    /**
     * Handle hash. Mainly scroll to buy area and show correct area. 
     *   
     * @param {type} hash
     * @returns {undefined}
     */
    function hashHandler(hash) {
      hash = hash.replace('#', '');
      if (hash === 'buy/private') {
        scrollToBuy();
        showPrivateHideCompany();
      }
      if (hash === 'buy/company') {
        scrollToBuy();
        showCompanyHidePrivate();
      }
    }



    /**
     * Custom Email ajax validation for the jQuery Validator plugin
     * Krillo 2012 
     */
    var emailFree = true;
    $.validator.addMethod("mmEmail", function(email, element) {
      email = $('#email1').val();
      var data = {
        typ: 'epost',
        varde: email
      };
      $.ajax({
        type: "POST",
        url: "/ajax/actions/validate.php",
        async: false,
        data: data,
        cache: false,
        timeout: 30000,
        error: function() {
          return true;
        }
      }).done(function(data) {
        if (data == "1") {
          //console.log('true');
          emailFree = true;
        } else {
          //console.log('false');
          emailFree = false;
        }
      });
      return emailFree;
    }, "Upptagen epostadress");


    /**
     * Custom alias ajax validation for the jQuery Validator plugin
     * Krillo 2012 
     */
    var anamnFree = true;
    $.validator.addMethod("mmAnamn", function(anamn, element) {
      anamn = $('#anamn').val();
      var data = {
        typ: 'anamn',
        varde: anamn
      };
      $.ajax({
        type: "POST",
        url: "/ajax/actions/validate.php",
        async: false,
        data: data,
        cache: false,
        timeout: 30000,
        error: function() {
          return true;
        }
      }).done(function(data) {
        if (data == "1") {
          //console.log('true');
          anamnFree = true;
        } else {
          //console.log('false');
          anamnFree = false;
        }
      });
      return anamnFree;
    }, "Upptaget alias, välj ett annat");



    /**
     * Do input validation
     * Fields that are hidden are not validated 
     * jQuery Validator plugin
     */
    var validator = $("#checkout").validate({
      errorClass: "invalid",
      validClass: "valid",
      rules: {
        "del-company": {
          required: true
        },
        "del-firstname": {
          required: true
        },
        "del-lastname": {
          required: true
        },
        "del-email": {
          required: true,
          email: true
        },
        "del-street1": {
          required: true
        },
        "del-zip": {
          required: true
        },
        "del-city": {
          required: true
        },
        "del-phone": {
          required: true
        },
        //private
        anamn: {
          required: true,
          maxlength: 20,
          minlength: 3,
          mmAnamn: true
        },
        firstname: {
          required: true
        },
        lastname: {
          required: true
        },
        email1: {
          required: true,
          email: true,
          mmEmail: true
        },
        email2: {
          email: '',
          equalTo: "#email1"
        },
        street1: {
          required: true
        },
        zip: {
          required: true
        },
        city: {
          required: true
        },
        phone: {
          required: true
        },
        pass: {
          required: true
                  //min: 4
        },
        pass2: {
          equalTo: "#pass"
        }
      },
      messages: {
        "del-company": {
          required: ''
        },
        "del-firstname": {
          required: ''
        },
        "del-lastname": {
          required: ''
        },
        "del-street1": {
          required: ''
        },
        "del-zip": {
          required: ''
        },
        "del-city": {
          required: ''
        },
        "del-phone": {
          required: ''
        },
        "del-email": {
          required: '',
          email: ''
        },
        //Private
        "anamn": {
          required: '',
          maxlength: 'För långt',
          minlength: 'För kort'
        },
        "firstname": {
          required: ''
        },
        "lastname": {
          required: ''
        },
        "street1": {
          required: ''
        },
        "zip": {
          required: ''
        },
        "city": {
          required: ''
        },
        "phone": {
          required: ''
        },
        "email1": {
          required: '',
          email: 'Skriv en korrekt e-postadress'
        },
        "email2": {
          equalTo: '',
          email: 'Skriv en korrekt e-postadress'
        },
        "pass": {
          required: ''
                  //min: 'minst 4 tecken'
        },
        "pass2": {
          equalTo: ''
        }
      }
    });


    function showPrivateHideCompany() {
      type = 'private';
      $('#type').val('private');
      $('.hide-company').addClass("hidden");
      $('#buy-company').addClass("hidden");
      $('#buy-company-top').removeClass("full-width");
      $('.hide-private').removeClass("hidden");
      $('#buy-private-top-left').addClass("full-width");
      $('#link-company').removeClass("hidden");
      $('#buy-payment').removeClass("hidden");
      $('#faktura').addClass("hidden");
    }

    function showCompanyHidePrivate() {
      type = 'company';
      $('#type').val('company');
      $('.hide-company').removeClass("hidden");
      $('#buy-company').removeClass("hidden");
      $('#buy-company-top').addClass("full-width");
      $('.hide-private').addClass("hidden");
      $('#buy-private-top-left').removeClass("full-width");
      $('#buy-payment').removeClass("hidden");
      $('#faktura').removeClass("hidden");
    }


    /**
     * Sum company with and without stepcounter, add freight and moms
     */
    function sum_company() {
      var sumWith, sumWithout, sumTotal, countWith, countWithout, freight, sumTotalFreight, sumTotalFreightMoms;
      showCompanyHidePrivate();

      countWith = $('#nbr-with').val();
      sumWith = countWith * r3_price;            //<?php echo $campaignCodes['RE03']['pris']; ?>;
      $('#nbr-with-sum span').html(sumWith);
      if (sumWith == 0) {
        //FRAKT00 is 0 kr
        $('#freight span').html(frakt00_price);  // <?php echo $campaignCodes['FRAKT00']['pris']; ?>);
        $('#freight-text').html(frakt00_extra);  //'<?php echo $campaignCodes['FRAKT00']['extra']; ?>');
        $('#m_freight').val('FRAKT00');
      } else {
        $('#freight span').html(frakt01_price);      //<?php echo $campaignCodes['FRAKT01']['pris']; ?>);
        $('#freight-text').html(frakt01_extra);      //'<?php echo $campaignCodes['FRAKT01']['extra']; ?>');
        $('#m_freight').val('FRAKT01');
      }
      countWithout = $('#nbr-without').val();
      sumWithout = countWithout * r4_price;    //<?php echo $campaignCodes['RE04']['pris']; ?>;
                 
                 
      $('#nbr-without-sum span').html(sumWithout);
      sumTotal = sumWith + sumWithout;
      $('#nbr-sum-total span').html(sumTotal);

      freight = $('#freight span').html();
      sumTotalFreight = parseInt(freight) + parseInt(sumTotal);
      $('#nbr-sum-total-freight-nbr').html(sumTotalFreight);
      $('#nbr-sum-total-freight span.nbr').html(sumTotalFreight);

      sumTotalFreightMoms = sumTotalFreight * moms_percent;
      sumTotalFreightMoms = Math.ceil(sumTotalFreightMoms);
      $('#nbr-sum-total-freight-moms span').html(sumTotalFreightMoms);

      $('#m_exmoms').val(sumTotal);
      $('#m_total').val(sumTotalFreight);
      $('#m_incmoms').val(sumTotalFreightMoms);
    }

    /**
     * Sum private
     */
    function sum_private() {
      showPrivateHideCompany();
      radio = $('input:radio[name=radio-priv]:checked').val();
      if (typeof radio != 'undefined') {  //one of the radios are checked
        shortRadio = priv3_price;                                        //<?php echo $campaignCodes['PRIV3']['pris']; ?>;
        longRadio = priv12_price                                          //<?php echo $campaignCodes['PRIV12']['pris']; ?>;

        if (radio == priv3_price){                                        //<?php echo $campaignCodes['PRIV3']['pris']; ?>) {
          $('#long-check').attr('checked', false);
          longRadio = 0;
          $('#m_priv3').val(1);
          $('#m_priv12').val(0);
        }
        if (radio == priv12_price){                                      //<?php echo $campaignCodes['PRIV12']['pris']; ?>) {
          $('#short-check').attr('checked', false);
          shortRadio = 0;
          $('#m_priv12').val(1);
          $('#m_priv3').val(0);
        }
        shortCheck = $('#short-check:checked').val();
        longCheck = $('#long-check:checked').val();
        if (typeof shortCheck == 'undefined') {
          shortCheck = 0;
        }
        if (typeof longCheck == 'undefined') {
          longCheck = 0;
        }
        //alert('radio: ' + radio + ' shortCheck: ' + shortCheck+ ' longCheck: ' + longCheck);
        if (shortCheck != 0 || longCheck != 0) {
          sumFreight = parseInt(frakt02_price);                          //<?php echo $campaignCodes['FRAKT02']['pris']; ?>);
          $('#m_frakt02').val(1);
          $('#m_steg01').val(1);
        } else {
          sumFreight = 0;
          $('#m_frakt02').val(0);
          $('#m_steg01').val(0);
        }
        sumShort = parseInt(shortRadio) + parseInt(shortCheck);
        sumLong = parseInt(longRadio) + parseInt(longCheck);
        sumTotal = sumShort + sumLong + sumFreight;

        $('#sum-short').html(sumShort);
        $('#sum-long').html(sumLong);
        $('#sum-freight').html(sumFreight);
        $('#sum-total').html(sumTotal);
        $('#priv-the-price').html(sumTotal);
        $('#m_total').val(sumTotal);
        $('#m_freight').val(sumFreight);
      } else { //radiobutton is undfined do nothing     
        //alert('apa');
      }
    }

    /**
     * Scroll so that buy is at top ov browser
     * @returns {Boolean}     
     */
    function scrollToBuy() {
      $('html, body').animate({
        scrollTop: $("#buy").offset().top - 60
      }, 1000);
      return true;
    }



    /******************************
     * Catch events
     ******************************/
    
    //if hash changes on url and still on same page
    $(window).on('hashchange', function() {
      var hash2 = location.hash;
      hashHandler(hash2);
    });


    //toggle from company to private    
    $('#link-private').click(function(event) {
      event.preventDefault();
      showPrivateHideCompany();
      scrollToBuy();
    });

    //toggle from private to company    
    $('#link-company').click(function(event) {
      event.preventDefault();
      showCompanyHidePrivate();
      scrollToBuy();
    });

    //company catch keyup where the ammount is submitted 
    $('#nbr-with').keyup(function() {
      scrollToBuy();
      sum_company();
    });
    $('#nbr-without').keyup(function() {
      scrollToBuy();
      sum_company();
    });

    //copmany check also when leaving field
    $('#nbr-with').blur(function() {
      sum_company();
    });
    $('#nbr-without').keyup(function() {
      sum_company();
    });

    //company show or hide delicery address       
    $('#delivery-toggle').click(function(event) {
      event.preventDefault();
      if ($('#delivery-address').hasClass("visible")) {
        $('#delivery-address').toggleClass("visible");
        $('#delivery-address').hide("slow");

      } else {
        $('#delivery-address').toggleClass("visible");
        $('#delivery-address').show("slow");
      }
    });


    //private monitor changes on radio and checkbox  
    $('#short-radio').change(function() {
      scrollToBuy();
      sum_private();
    });
    $('#long-radio').change(function() {
      scrollToBuy();
      sum_private();
    });
    $('#short-check').change(function() {
      scrollToBuy();
      sum_private();
    });
    $('#long-check').change(function() {
      scrollToBuy();
      sum_private();
    });


    //send form to company or private
    $("#checkout").submit(function() {
      if (type == "company") {
        $("#checkout").attr("action", "/actions/payson_foretag.php");
        return true;
      } else {
        $("#checkout").attr("action", "/actions/payson_privat.php")
        return true;
      }
    });


  });


  function updateStartRadio() {
    jQuery("#startdatumRadio2").attr('checked', true);
  }
