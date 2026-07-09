<?php
include("db_connect.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    
</head>
<style>
       body{
    font-family: Arial, sans-serif;
    background-color: #f4f6f9;
    margin: 20px;
}

h2{
    text-align: center;
    color: #333;
}

table{
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
}

th{
    background-color: #007bff;
    color: white;
    padding: 12px;
    text-align: center;
}

td{
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

tr:nth-child(even){
    background-color: #f8f9fa;
}

tr:hover{
    background-color: #e9f5ff;
    transition: 0.3s;
}
button{
            width: 100%;
            padding: 10px ;
            background-color:#00bcd4;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }
        button:hover{
            background-color: #0097a7;
            transform: scale(1.05);
        }
</style>
<body>
    <?php
include("db_connect.php");
?>

<?php
if (isset($_GET['msg']) && $_GET['msg'] == "updated") {
    echo "<p style='color:green;
                  background:#d4edda;
                  padding:10px;
                  border:1px solid green;
                  border-radius:5px;'>
            ✅ Student Updated Successfully!
          </p>";
}
?>


    <table border="1">
    <tr>
            <th>ID</th>
            <th>Name</th>
            <th>E-mail</th>
            <th>Branch</th>
            <th>College</th>
            <th>Action</th>
    </tr>
        <?php
        $sql="SELECT * FROM students";
        $result=mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result))
        {
            ?>
            <tr>
                <td><?php echo $row['id'];?></td>
                <td><?php echo $row['name'];?></td>
                <td><?php echo $row['email'];?></td>
                <td><?php echo $row['branch'];?></td>
                <td><?php echo $row['college'];?></td>
                <td>
                    <button>
                <a href="edit.php?id=<?php echo $row['id']; ?>"
                 onclick="return confirm('Are you sure you want to edit this this record? This action cannot be undone.');">
                 edit
                </a></button>
                </td>
                </tr>
            <?php
        }
        ?>


</table>

</body>
</html>