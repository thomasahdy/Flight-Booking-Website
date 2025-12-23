


let name = currentUser.name;
let email = currentUser.email;
let photo = currentUser.photo;
let tel = currentUser.tel;
let passportImg = currentUser.passportImg;
let username = currentUser.username;

console.log(emailElem);
console.log("Hello world");

const userPhoto = document.getElementById("userPhoto");
const userName = document.getElementById("userName");
const userEmail = document.getElementById("userEmail");
const userTel = document.getElementById("userTel");
const userPassportImg = document.getelementById("userTel");

userPhoto.setAttribute("src", photo);
userName.innerHTML = name;
userEmail.innerHTML = email;
userTel.innerHTML = tel;
userPassportImg.setAttribute("src", passportImg);



const emailElem = document.getElementById("email")
const nameElem = document.getElementById("name")
const telElem = document.getElementById("tel")
const photoElem = document.getElementById("photo")
const usernameElem = document.getElementById("username")
const passportImgElem = document.getElementById("passportImg")


emailElem.value = email;
nameElem.value = name;
telElem.value = tel;
usernameElem.value = userName;


//fetch



