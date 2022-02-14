<?php

// define variables and set to empty values
$nameErr = $emailErr = $genderErr = $websiteErr = $passErr = $conpassErr = $checkErr = $response = "";
$name = $email = $password = $con_password = $gender = $comment = $website = $checkbox = $file = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $passErr = "Password is required";
    } else {
        $password = $_POST["password"];
        if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password)) {
            $passErr = "Invalid password format";
        }
    }

    if ($_POST["password"] != $_POST["con_password"]) {
        $conpassErr = "Password did not matched";
    }


    if (empty($_POST["website"])) {
        $website = "";
    } else {
        $website = test_input($_POST["website"]);
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $website)) {
            $websiteErr = "Invalid URL";
        }
    }

    if (empty($_POST["comment"])) {
        $comment = "";
    } else {
        $comment = test_input($_POST["comment"]);
    }

    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    if (empty($_POST["checkbox"])) {
        $checkErr = "Please accept the terms and conditions";
    } else {
        $checkbox = test_input($_POST["checkbox"]);
    }
    $file = $_FILES['file'];
    if (isset($_FILES['file'])) {

        $allowed_image_extension = array(
            "png",
            "jpg",
            "jpeg"
        );

        $file_name = $_FILES['file']['name'];
        $file_type = $_FILES['file']['type'];
        $file_size = $_FILES['file']['size'];
        $file_tmp_name = $_FILES['file']['tmp_name'];
        $image_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!file_exists($_FILES["file"]["tmp_name"])) {
            $response = array(
                "type" => "error",
                "message" => "Choose image file to upload."
            );
        } else if (!in_array($image_extension, $allowed_image_extension)) {
            $response = array(
                "type" => "error",
                "message" => "Upload valid images. Only PNG and JPEG are allowed."
            );
        } else if (($_FILES["file"]["size"] > 2000000)) {
            $response = array(
                "type" => "error",
                "message" => "Image size exceeds 2MB"
            );
        } else {

            move_uploaded_file($file_tmp_name, "uploads/" . time() . ".png");
        }
    }
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valid Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .error {
            color: #FF0000;
        }

        #submit_btn {
            padding: 7px 10px;
            background: gray;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            font-weight: 500px;
        }

        #submit_btn:hover {
            background: #008CBA;
        }
    </style>
</head>

<body>
    <h2 class="text-center">PHP Form Validation</h2>
    <div class="row p-3">
        <div class="col-md-6 m-auto border border-primary">
            <?php echo '<h2 class="text-center p-4">Our Input here:</h2>'; ?>
            <form class="" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <br>
                <input type="text" name="name" value="<?php echo $name; ?>">
                <span class="error"> *</span>
                <br>
                <span class="error"><?php echo $nameErr; ?></span>
                <br><br>
                <label for="email">Email:</label>
                <br>
                <input type="text" name="email" value="<?php echo $email; ?>">
                <span class="error"> *</span>
                <br>
                <span class="error"><?php echo $emailErr; ?></span>
                <br><br>
                <label for="password">Password:</label>
                <br>
                <input type="password" name="password">
                <span class="error"> *</span>
                <br>
                <span class="error"><?php echo $passErr; ?></span>
                <br><br>
                <label for="con-password">Confirm Password:</label>
                <br>
                <input type="password" name="con_password">
                <span class="error"> *</span>
                <br>
                <span class="error"><?php echo $conpassErr; ?></span>
                <br><br>
                <label for="website">Website:</label>
                <br>
                <input type="text" name="website" value="<?php echo $website; ?>">
                <span class="error"><?php echo $websiteErr; ?></span>
                <br><br>
                <label for="comment">Comment:</label>
                <br>
                <textarea name="comment" rows="5" cols="40"><?php echo $comment; ?></textarea>
                <br><br>
                <label for="gender">Gender:</label>
                <input type="radio" name="gender" <?php if (isset($gender) && $gender == "female") echo "checked"; ?> value="female">Female
                <input type="radio" name="gender" <?php if (isset($gender) && $gender == "male") echo "checked"; ?> value="male">Male
                <input type="radio" name="gender" <?php if (isset($gender) && $gender == "other") echo "checked"; ?> value="other">Other
                <span class="error"> *</span>
                <br>
                <span class="error"><?php echo $genderErr; ?></span>
                <br><br>
                <input type="file" name="file">
                <br>
                <?php if (!empty($response)) { ?>
                    <div class="response <?php echo $response["type"]; ?>
    ">
                        <?php echo $response["message"]; ?>
                    </div>
                <?php } ?>
                <br><br>
                <input type="checkbox" name="checkbox" id="">
                <label for="">I acccept terms and conditions</label>
                <br>
                <span class="error"><?php echo $checkErr; ?></span>
                <br><br>
                <div class="text-center p-2">
                    <input id="submit_btn" type="submit" name="submit" value="Submit">
                </div>
            </form>
        </div>
        <div class="col-md-6 border border-primary">
            <?php
            echo '<h2 class="text-center p-4">Our Output here:</h2>';
            echo $name;
            echo "<br>";
            echo $email;
            echo "<br>";
            echo $password;
            echo "<br>";
            echo $con_password;
            echo "<br>";
            echo $website;
            echo "<br>";
            echo $comment;
            echo "<br>";
            echo $gender;
            echo "<br>";
            echo $checkbox;
            echo "<br>";
            echo "<pre>";
            print_r($file);
            echo "</pre>";
            ?>
        </div>
    </div>

    <!-- script -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>