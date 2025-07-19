<!DOCTYPE html>
<html>
<head>
  <title>Book Now</title>
  <style>
    body {
      height: 100%;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #222;
    }

    .container {
      background-color: #b1ff9e;
      width: 100vw;
      height: 100vh; 
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form-box {
      background-color: #55f35b;
      padding: 30px;
      width: 100%;
      max-width: 1000px;
      border-radius: 10px;
    }

    h2 {
      text-align: center;
      margin-top: 0;
    }

    .form-row {
      display: flex;
      gap: 20px;
      margin-bottom: 15px;
    }

    .form-group {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    input[type=text], input[type=number], input[type=email] {
      padding: 10px;
      border: none;
      border-radius: 5px; 
    }

    input[type=submit] {
      margin-top: 10px;
      padding: 10px 15px;
      border: none;
      background-color: #fff;
      cursor: pointer;
      font-weight: bold;
    }
    input[type=submit]:hover {
      background-color: #333;
      color: #fff;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="form-box">
    <h2>Book Now</h2>
    <form action="carddetails.php" method="POST">
      <div class="form-row">
        <div class="form-group">
          <label>First Name</label>
          <input type="text" name="fname" placeholder="First Name" required>
        </div>
        <div class="form-group">
          <label>Last Name</label>
          <input type="text" name="lname" placeholder="Last Name" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" placeholder="Email Address" required>
        </div>
        <div class="form-group">
          <label>Address</label>
          <input type="text" name="address" placeholder="Home Address" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>How many tickets</label>
          <input type="number" name="tickets" placeholder="How many ticket" required>
        </div>
        <div class="form-group">
       
        </div>
      </div>

      <input type="submit" name="continue" value="Continue">
    </form>
  </div>
</div>

</body>
</html>