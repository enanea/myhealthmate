<?php
include('config.php');

// Start the session to access user data
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Kira skor total berdasarkan jawapan
    $total_score = 0;
    for ($i = 1; $i <= 15; $i++) {
        $question = "question" . $i;
        if (isset($_POST[$question])) {
            $total_score += intval($_POST[$question]);
        }
    }

    // Pastikan pengguna telah log masuk
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username']; // Dapatkan nama pengguna dari sesi

        // Dapatkan ID pengguna berdasarkan nama pengguna
        $sql = "SELECT id FROM users WHERE username='$username'";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user_id = $row['id']; // Ambil ID pengguna
        } else {
            echo "<script>alert('User not found.');</script>";
            exit;
        }

        // Tentukan jika mereka perlu berjumpa kaunselor
        $need_counseling = ($total_score > 20) ? 'Yes' : 'No'; // Ambang skor 20 (contoh)

        // Insert data ke dalam database
        $sql = "INSERT INTO mentaltest (user_id, name, email, total_score, need_counseling) 
                VALUES ('$user_id', '$name', '$email', '$total_score', '$need_counseling')";

        if ($mysqli->query($sql)) {
    echo "<div class='result-container'>";
    echo "<h3 class='result-header'>Data saved successfully!</h3>";
    echo "<p class='result-text'>Result: Need to see a counselor? <strong class='result-highlight'>" . $need_counseling . "</strong></p>";
    echo "</div>";
} else {
    echo "Error: " . $mysqli->error;
}
} else {
    echo "<script>alert('You need to log in to submit data.');</script>";
}
}

$mysqli->close();
?>


<!DOCTYPE html>
<html lang="ms">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
     <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form for Mental Health Assessment</title>
    <link rel="stylesheet">
</head>

<style>
    /* General reset */
body {
    margin: 0;
    font-family: 'Arial', sans-serif;
    background-color: #ffe5e9; /* Soft pink */
    color: #4b0e24; /* Maroon text */
    padding: 0;
}

h1 {
    text-align: center;
    color: #4b0e24; /* Maroon */
    font-size: 2.5em;
    margin-top: 20px;
}

form {
    max-width: 600px;
    background: #fff;
    border: 2px solid #4b0e24; /* Maroon border */
    border-radius: 10px;
    padding: 20px 30px;
    margin: 30px auto;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

label {
    display: block;
    font-size: 1.1em;
    margin-top: 15px;
    margin-bottom: 8px;
    color: #4b0e24; /* Maroon */
}

input[type="text"], 
input[type="email"], 
button {
    width: 100%;
    padding: 10px;
    margin: 5px 0 15px 0;
    border: 1px solid #4b0e24; /* Maroon */
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 1em;
}

input[type="radio"] {
    margin-right: 10px;
}

button {
    background-color: #4b0e24; /* Maroon */
    color: #fff;
    font-size: 1.2em;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #732541; /* Darker maroon */
}

h3 {
    color: #732541; /* Darker maroon */
    margin-top: 20px;
    margin-bottom: 10px;
    font-size: 1.3em;
    border-bottom: 2px solid #4b0e24; /* Maroon */
    display: inline-block;
    padding-bottom: 5px;
}

input[type="text"]:focus,
input[type="email"]:focus {
    outline: none;
    border: 1px solid #732541; /* Focus effect */
    box-shadow: 0 0 5px rgba(115, 37, 65, 0.5);
}

form input[type="radio"]:hover + label {
    color: #732541;
}

@media (max-width: 768px) {
    form {
        padding: 15px;
        font-size: 0.9em;
    }

    h1 {
        font-size: 2em;
    }
}

a.kembali {
    display: block; /* Makes the link take up the full width */
    text-align: center; /* Centers the text within the block */
    margin-top: 15px; /* Optional spacing above the link */
    font-size: 1em; /* Adjust font size if needed */
    color: #4b0e24; /* Matches the maroon color scheme */
    text-decoration: none; /* Removes underline from the link */
}

a.kembali:hover {
    color: #732541; /* Darker maroon for hover effect */
    text-decoration: underline; /* Optional: underline on hover */
}

/*echo*/
.result-container {
    background-color: #f0f8ff; /* Light blue background */
    border: 2px solid #0077cc; /* Blue border */
    border-radius: 10px;
    padding: 20px;
    margin: 20px auto;
    width: 60%; /* Adjust the width */
    text-align: center;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.result-header {
    color: #004a99; /* Dark blue for the header */
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.result-text {
    color: #333; /* Neutral text color */
    font-size: 1.2rem;
}

.result-highlight {
    color: #0077cc; /* Highlight important text */
    font-weight: bold;
}
/* footer */
        .footer {
            background: linear-gradient(135deg, #f5cec8, #842029);
            color: #fff;
            padding: 40px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .footer-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-section {
            flex: 1;
            margin: 10px;
            min-width: 250px;
        }

        .footer-section h2 {
            font-size: 18px;
            margin-bottom: 15px;
            
        }

        .footer-section p {
            line-height: 1.6;
        }

        .footer-section ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section ul li a {
            color: #f5cec8;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .footer-section ul li a:hover {
            color: #842029;
            background-color: #f5cec8;
            padding: 2px 5px;
            border-radius: 4px;
        }

        .footer-section ul li i {
            margin-right: 8px;
            color: #f5cec8;
        }

        .social {
            display: inline-block;
            margin: 0 10px;
            color: #f5cec8;
            font-size: 20px;
            transition: color 0.3s ease;
        }

        .social:hover {
            color: #842029;
        }

        .footer-bottom {
            text-align: center;
            padding: 10px 20px;
            background-color: #842029;
            color: #e3f2fd;
            font-size: 12px;
            margin-top: 20px;
        }

        .back-to-top {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-top a {
            color: #f5cec8;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-to-top a:hover {
            color: #842029;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                align-items: center;
            }

            .footer-section {
                text-align: center;
                margin: 20px 0;
            }

</style>
<body>
    <h1>Mental Form Page</h1>
    <form action="mental.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br>

        <h3>Sila jawab semua soalan berikut dengan JUJUR : </h3>
        <!-- Questions start -->
        <label>1. Saya sering berasa risau jika rakan-rakan tidak suka saya.</label><br>
        <input type="radio" name="question1" value="1" required>Selalu<br>
        <input type="radio" name="question1" value="2">Kerap<br>
        <input type="radio" name="question1" value="3">Kadang-kadang<br>
        <input type="radio" name="question1" value="4">Jarang<br>
        <input type="radio" name="question1" value="5">Tidak Pernah<br>

        <label>2. Saya rasa gugup atau takut untuk bercakap dalam kumpulan rakan.</label><br>
        <input type="radio" name="question2" value="1" required>Selalu<br>
        <input type="radio" name="question2" value="2">Kerap<br>
        <input type="radio" name="question2" value="3">Kadang-kadang<br>
        <input type="radio" name="question2" value="4">Jarang<br>
        <input type="radio" name="question2" value="5">Tidak Pernah<br>

        <label>3. Saya berasa tertekan apabila melihat rakan-rakan lebih berjaya atau disukai.</label><br>
        <input type="radio" name="question3" value="1" required>Selalu<br>
        <input type="radio" name="question3" value="2">Kerap<br>
        <input type="radio" name="question3" value="3">Kadang-kadang<br>
        <input type="radio" name="question3" value="4">Jarang<br>
        <input type="radio" name="question3" value="5">Tidak Pernah<br>

        <label>4. Saya selalu berpura-pura gembira di depan rakan.</label><br>
        <input type="radio" name="question4" value="1" required>Selalu<br>
        <input type="radio" name="question4" value="2">Kerap<br>
        <input type="radio" name="question4" value="3">Kadang-kadang<br>
        <input type="radio" name="question4" value="4">Jarang<br>
        <input type="radio" name="question4" value="5">Tidak Pernah<br>

        <label>5. Saya rasa tidak layak untuk menjadi sebahagian daripada kumpulan rakan saya.</label><br>
        <input type="radio" name="question5" value="1" required>Selalu<br>
        <input type="radio" name="question5" value="2">Kerap<br>
        <input type="radio" name="question5" value="3">Kadang-kadang<br>
        <input type="radio" name="question5" value="4">Jarang<br>
        <input type="radio" name="question5" value="5">Tidak Pernah<br>

        <label>6. Saya merasa cemas setiap kali perlu bercakap dengan guru.</label><br>
        <input type="radio" name="question6" value="1" required>Selalu<br>
        <input type="radio" name="question6" value="2">Kerap<br>
        <input type="radio" name="question6" value="3">Kadang-kadang<br>
        <input type="radio" name="question6" value="4">Jarang<br>
        <input type="radio" name="question6" value="5">Tidak Pernah<br>

        <label>7. Saya rasa murung apabila usaha saya tidak dihargai oleh guru.</label><br>
        <input type="radio" name="question7" value="1" required>Selalu<br>
        <input type="radio" name="question7" value="2">Kerap<br>
        <input type="radio" name="question7" value="3">Kadang-kadang<br>
        <input type="radio" name="question7" value="4">Jarang<br>
        <input type="radio" name="question7" value="5">Tidak Pernah<br>

        <label>8. Saya berasa penat dengan beban tugasan yang diberi guru.</label><br>
        <input type="radio" name="question8" value="1" required>Selalu<br>
        <input type="radio" name="question8" value="2">Kerap<br>
        <input type="radio" name="question8" value="3">Kadang-kadang<br>
        <input type="radio" name="question8" value="4">Jarang<br>
        <input type="radio" name="question8" value="5">Tidak Pernah<br>

        <label>9. Saya rasa guru lebih memberi perhatian kepada pelajar lain berbanding saya.</label><br>
        <input type="radio" name="question9" value="1" required>Selalu<br>
        <input type="radio" name="question9" value="2">Kerap<br>
        <input type="radio" name="question9" value="3">Kadang-kadang<br>
        <input type="radio" name="question9" value="4">Jarang<br>
        <input type="radio" name="question9" value="5">Tidak Pernah<br>

        <label>10. Saya tidak mempercayai untuk meluah pada mana mana guru.</label><br>
        <input type="radio" name="question10" value="1" required>Selalu<br>
        <input type="radio" name="question10" value="2">Kerap<br>
        <input type="radio" name="question10" value="3">Kadang-kadang<br>
        <input type="radio" name="question10" value="4">Jarang<br>
        <input type="radio" name="question10" value="5">Tidak Pernah<br>

        <label>11. Saya sering merasa risau tentang perkara kecil yang orang lain tidak kisahkan.</label><br>
        <input type="radio" name="question11" value="1" required>Selalu<br>
        <input type="radio" name="question11" value="2">Kerap<br>
        <input type="radio" name="question11" value="3">Kadang-kadang<br>
        <input type="radio" name="question11" value="4">Jarang<br>
        <input type="radio" name="question11" value="5">Tidak Pernah<br>

        <label>12. Saya rasa tidak selesa berinteraksi walaupun dengan orang yang saya kenal.</label><br>
        <input type="radio" name="question12" value="1" required>Selalu<br>
        <input type="radio" name="question12" value="2">Kerap<br>
        <input type="radio" name="question12" value="3">Kadang-kadang<br>
        <input type="radio" name="question12" value="4">Jarang<br>
        <input type="radio" name="question12" value="5">Tidak Pernah<br>

        <label>13. Saya sering merasa murung tanpa sebab yang jelas.</label><br>
        <input type="radio" name="question13" value="1" required>Selalu<br>
        <input type="radio" name="question13" value="2">Kerap<br>
        <input type="radio" name="question13" value="3">Kadang-kadang<br>
        <input type="radio" name="question13" value="4">Jarang<br>
        <input type="radio" name="question13" value="5">Tidak Pernah<br>

        <label>14. Saya sering berasa sangat penat walaupun tidak melakukan banyak aktiviti.</label><br>
        <input type="radio" name="question14" value="1" required>Selalu<br>
        <input type="radio" name="question14" value="2">Kerap<br>
        <input type="radio" name="question14" value="3">Kadang-kadang<br>
        <input type="radio" name="question14" value="4">Jarang<br>
        <input type="radio" name="question14" value="5">Tidak Pernah<br>

        <label>15. Saya rasa diri saya tidak cukup baik berbanding orang lain.</label><br>
        <input type="radio" name="question15" value="1" required>Selalu<br>
        <input type="radio" name="question15" value="2">Kerap<br>
        <input type="radio" name="question15" value="3">Kadang-kadang<br>
        <input type="radio" name="question15" value="4">Jarang<br>
        <input type="radio" name="question15" value="5">Tidak Pernah<br>

        <label>16. Saya berasa takut untuk berkongsi perasaan saya dengan keluarga.</label><br>
        <input type="radio" name="question16" value="1" required>Selalu<br>
        <input type="radio" name="question16" value="2">Kerap<br>
        <input type="radio" name="question16" value="3">Kadang-kadang<br>
        <input type="radio" name="question16" value="4">Jarang<br>
        <input type="radio" name="question16" value="5">Tidak Pernah<br>

        <label>17. Saya sering merasa tidak difahami oleh ahli keluarga saya.</label><br>
        <input type="radio" name="question17" value="1" required>Selalu<br>
        <input type="radio" name="question17" value="2">Kerap<br>
        <input type="radio" name="question17" value="3">Kadang-kadang<br>
        <input type="radio" name="question17" value="4">Jarang<br>
        <input type="radio" name="question17" value="5">Tidak Pernah<br>

        <label>18. Saya merasa tertekan apabila berada di rumah dan tidak mempunyai ketenangan.</label><br>
        <input type="radio" name="question18" value="1" required>Selalu<br>
        <input type="radio" name="question18" value="2">Kerap<br>
        <input type="radio" name="question18" value="3">Kadang-kadang<br>
        <input type="radio" name="question18" value="4">Jarang<br>
        <input type="radio" name="question18" value="5">Tidak Pernah<br>

        <label>19. Saya cepat letih bila bersama keluarga kerana banyak beban emosi.</label><br>
        <input type="radio" name="question19" value="1" required>Selalu<br>
        <input type="radio" name="question19" value="2">Kerap<br>
        <input type="radio" name="question19" value="3">Kadang-kadang<br>
        <input type="radio" name="question19" value="4">Jarang<br>
        <input type="radio" name="question19" value="5">Tidak Pernah<br>

        <label>20. Saya rasa tidak cukup baik di mata keluarga saya.</label><br>
        <input type="radio" name="question20" value="1" required>Selalu<br>
        <input type="radio" name="question20" value="2">Kerap<br>
        <input type="radio" name="question20" value="3">Kadang-kadang<br>
        <input type="radio" name="question20" value="4">Jarang<br>
        <input type="radio" name="question20" value="5">Tidak Pernah<br>
        <!-- Questions end -->

        <button type="submit">Submit</button>
        <a class="kembali" href="index.php" type="link">Return to Main Page</a>
    </form>

<footer class="footer">
        <div class="footer-content">
            <!-- About Us Section -->
            <div class="footer-section">
                <h2>About Us</h2>
                <p>Portal Laman Kesihatan Kolej Vokasional Sepang aims to provide students with essential health information and tools for well-being.</p>
            </div>
            <!-- Quick Links Section -->
            <div class="footer-section">
                <h2>Quick Links</h2>
                <ul>
                    <li><a href="#">BMI Calculator</a></li>
                    <li><a href="#">Calorie Tracker</a></li>
                    <li><a href="#">Contact Us</a></li>
                </ul>
            </div>
            <!-- Contact Section -->
            <div class="footer-section">
                <h2>Contact</h2>
                <ul>
                    <li><i class="fas fa-envelope"></i> Email: healthportal@kvsepang.edu.my</li>
                    <li><i class="fas fa-phone"></i> Phone: +60 123-456-7890</li>
                    <li><i class="fas fa-map-marker-alt"></i> Address: KV Sepang, Malaysia</li>
                </ul>
            </div>
            <!-- Social Media Section -->
            <div class="footer-section">
                <h2>Follow Us</h2>
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-twitter"></i></a>
                <a href="#" class="social"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <!-- Back to Top -->
        <div class="back-to-top">
            <a href="#">↑ Back to Top</a>
        </div>
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            &copy; 2024 Portal Laman Kesihatan Kolej Vokasional Sepang. All rights reserved.
        </div>
    </footer>
</body>
</html>
