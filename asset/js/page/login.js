// var base_url = window.location.origin;

// function loginProcess(){
//     var username = document.getElementById("username").value;
//     var password = document.getElementById("password").value;
//     // if (username == "admin" && password == "admin123"){
//     //     alert(username, password);
//     // }
//     // else{
//     //     alert("username or password incorrect");
//     // }
//     $.ajax({
//         url: base_url + "/saferoutefinpro/login/login_action",
//         method:"POST",
//         data: {username:username, password:password},
//         success:function(response){
//             console.log("Sukses");
//         }
//     });
// }

const passwordInput = document.querySelector("#password")
const eye = document.querySelector("#eye");

eye.addEventListener("click", function(){
    this.classList.toggle("fa-eye-slash")
    const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
    passwordInput.setAttribute("type", type)
  });
  