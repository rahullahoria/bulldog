function validatePhone(fld) {    
  var filter = /^([7-9][0-9]{9})+$/;
  if (fld.value == "") {
    return false;
  } 
  else if (!(filter.test(fld))) {
    return false ;
  } 
  else if (!(fld.length == 10)) {
    return false;
  }
  else {
    return true ;
  }
}
function validateEmail(fld) {
  var tfld = trim(fld);                        // value of field with whitespace trimmed off
  var emailFilter = /^[^@]+@[^@.]+\.[^@]*\w\w$/ ;
  if (!emailFilter.test(tfld)) {              //test email for illegal characters
    return false;
  } 
  else {
    return true;
  }
}
function subscribe() {
  $("#subscribe_submit").attr('disabled','disabled');
  var email = $("#email_subscribe").val();
  if (!validateEmail(email)){
    alert("Please enter valid email");
    $("#subscribe_submit").removeAttr('disabled');
  }
  else {
    $.ajax({
      type: "POST",
      url: "ajax/subscribe.php",
      data: "email=" + email,
      cache: false,
      success: function(result){
        alert("Subscribed Successfully");
        $("#email_subscribe").val("");
      },
      error: function(result){
        alert("result");
        return false;
      }
    });
    return false;
  }
}
function signup() {
  $("#signup_form_submit").attr('disabled','disabled');
  var name = $("#header_form_name").val();
  var email = $("#header_form_email").val();
  var mobile = $("#header_form_mobile").val();
  if (!validateFirstname(name)){
    alert("Please enter valid name");
    $("#signup_form_submit").removeAttr('disabled');
  }
  else if (!validateEmail(email)){
    alert("Please enter valid email");
    $("#signup_form_submit").removeAttr('disabled');
  }
  else if (!validatePhone(mobile)){
    alert("Please enter valid mobile number");
    $("#signup_form_submit").removeAttr('disabled');
  }
  else {
    var dataString = "name=" + name + "&email=" + email + "&mobile=" + mobile ;
    $.ajax({
      type: "POST",
      url: "ajax/signup.php",
      data: dataString,
      cache: false,
      success: function(result){
        alert("Added Successfully");
        location.reload();
      },
      error: function(result){
        alert("result");
        return false;
      }
    });
    return false;
  }
}
function nospaces(t){
  if(t.value.match(/\s/g)){
    alert('Sorry, you are not allowed to enter any spaces');
    t.value=t.value.replace(/\s/g,'');
  }
}
function trim(s){
  return s.replace(/^\s+|\s+$/, '');
}
function validateFirstname(fld) {
  var illegalChars = /\W/; // allow letters, numbers, and underscores
  if (fld.value == "") {
    return false ;
  }  
  else if (fld.length < 3) {
    return false ;
  }  
  else if (illegalChars.test(fld.value)) {
    return false ;
  } 
  else {
    return true ;
  }
}