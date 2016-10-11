//JavaScript Document
function validasi(form){
if (formlogin.username.value == ""){
alert("Anda belum mengisikan Nama pengguna");
formlogin.username.focus();
return false;
}
     
if (formlogin.password.value == ""){
alert("Anda belum mengisikan Kata sandi");
formlogin.password.focus();
return false;
}
return true;
}