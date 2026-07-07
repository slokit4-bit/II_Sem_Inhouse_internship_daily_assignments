<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <style>
        h1{

            margin-bottom: 20px;
            text-align: center;
            color: white;
            text-shadow: 2px 2px 5px black;
            font-size: 32px; 
        }
        h2{

            margin-bottom: 20px;
            text-align: center;
            color: white;
            text-shadow: 2px 2px 5px black;
            font-size: 20px; 
        }
         h3{

            margin-bottom: 20px;
            text-align: fix;
            color: white;
            text-shadow: 2px 2px 5px black;
            font-size: 18px; 
        }
        h4{

            margin-bottom: 20px;
            /* text-align: center; */
            color: wheat;
            text-shadow: 2px 2px 5px black;
            font-size: 16px; 
        }
    .container{
            width:400px;
            background-color: #051b32;
            border:1px solid #5026cd;
            border-radius: 15px;
            padding: 25px 30px 25px 30px;
            margin: 20px auto;
            box-shadow: 0 10 25px rgba(0,0,0,0.5);
        }
        .footer {
  background-color: rgb(49, 17, 17);
  color: white;
  padding: 20px;
}
 button{
            width: 100%;
            padding: 10px ;
            background-color:black;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        button:hover{
            background-color: #8a7da3;
            transform: scale(1.05);
        }
        body{
    background-color: white;
    color: black;
    transition: 0.3s;
}

.dark{
    background-color: #121212;
    color: white;
}

#themeBtn{
    padding:10px 20px;
    border:none;
    border-radius:8px;
    cursor:pointer;
}
    </style>
   
    <div class="container">
     <button id="themeBtn">🌙 Dark Mode</button>
<h1><?php
$name=trim($_POST['name']);
$email=trim($_POST['email']);
$mobile_number=trim($_POST['mobile_number']);
$password=trim($_POST['password']);
$confirm_password=trim($_POST['confirm_password']);
if(empty($name)){
    echo "name is required";
} elseif(!filter_var($_POST['email'])){
    echo "invalid email id";
} elseif  (!preg_match("/^[6-9][0-9]{9}$/", $mobile_number)) {
    echo "Invalid mobile number";
}elseif(strlen($password)<8 ){
    echo "password must be 8 character long";
}elseif($password!=$confirm_password){
    echo "password doesnot match";
}else{
   echo"🎉 Registration Successful!";
   echo "</br>";
}

?></h1>
<hr>
<h2>Your details are</h2>
<br>
<h3><?php

echo "Name = ". $_POST['name'];
echo "<br>";
echo "College = ". $_POST['college'];
echo "<br>";
echo "Branch = ". $_POST['branch'];
echo "<br>";
echo "Mobile number = ". $_POST['mobile_number'];
echo "<br>";
echo "E-mail = ". $_POST['email'];
echo "<br>";
?></h3>
<h4><?php
date_default_timezone_set("Asia/Kolkata");
echo "Registration Date: " . date("d-m-Y");
echo "<br>";
echo "Registration Time: " . date("h:i:s A");
?></h4>
<button onclick="window.print()">Print Details</button>
<br><br><br>
<a href="thord.php">
    <button>Go Home</button>
</a>
</div>
    <footer class="footer">
    <p>©️ 2026 register form</p>
  </footer>
  <script>
        const btn = document.getElementById("themeBtn");

if(localStorage.getItem("theme") === "dark"){
    document.body.classList.add("dark");
    btn.innerHTML = "☀️ Light Mode";
}

btn.addEventListener("click", function(){

    document.body.classList.toggle("dark");

    if(document.body.classList.contains("dark")){
        localStorage.setItem("theme","dark");
        btn.innerHTML = "☀️ Light Mode";
    }else{
        localStorage.setItem("theme","light");
        btn.innerHTML = "🌙 Dark Mode";
    }

});
    </script>
</body>
</html>