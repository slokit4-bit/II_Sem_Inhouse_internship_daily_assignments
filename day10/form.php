a<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</head>
<body>
    <style>
        body{
            background-color: #16202b;
            margin: 0;
            padding:0;
        }
        
        p ,label{
            color: white;
            margin-top:15px;
            margin-bottom: 5px;
            font-size: 16px;
            font-weight: bold;
        }
        .container{
            width:400px;
            background-color: #2c3e50;
            border:1px solid #2a4e65;
            border-radius: 15px;
            padding: 25px 30px 25px 30px;
            margin: 20px auto;
            box-shadow: 0 10 25px rgba(0,0,0,0.5);
        }
         h1{

            margin-bottom: 20px;
            text-align: center;
            color: white;
            text-shadow: 2px 2px 5px black;
            font-size: 32px; 
        }
        h4{

            margin-bottom: 20px;
            /* text-align: center; */
            color: white;
            text-shadow: 2px 2px 5px black;
            font-size: 18px; 
        }
        input[type="text"],
         input[type="email"], 
         input[type="number"],
         input[type="password"],
         input[type="confirm_password"],
           select,
            textarea{
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
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
            background-color: #214246;
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
    <h1>Registerstion Form </h1>
    <form action="process.php" method="post">
        <h4>Enter your name</h4>
        <input type="text" id="name" name ="name" placeholder="Enter your name">
        <br>
        <h4>Enter your email</h4>
        <input type="text" id="email" name="email" placeholder="Enter your email ID">
        <br>
         <h4>Enter your college name</h4>
        <input type="text"  id="college" name ="college" placeholder="Enter your college name">
        <br>
         <h4>Enter your branch name</h4>
        <input type="text" id="branch" name ="branch" placeholder="Enter you branch">
        <br>
        <h4>Enter your phone number</h4>
        <input type ="number" id=" mobile_number" name ="mobile_number" placeholder="Enter your mobile number">
        <br>
        <h4>Enter your password</h4>
        <input type="password" id="password" name="password" placeholder="Enter your password">
        <br>
        <h4>Re-enter your password</h4>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Enter password again">
        <br><br>
        <button type="submit">SUBMIT FORM</button> 
        

    </form>
   
    </div>
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