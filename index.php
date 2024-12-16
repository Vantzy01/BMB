<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMB Internet Service</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

    <style>
        header nav ul {
        list-style: none;
        display: flex;
        gap: 30px;
        margin: 0;
        padding: 0;
        margin-right: 100px;
        }
        /* Additional Styles for Login Button */
        .login-btn {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-btn:hover {
            background-color: #2980b9;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: black;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            text-align: center;
        }
        .modal-content button {
            margin-top: 10px;
            padding: 10px 15px;
            border: none;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-content button:hover {
            background-color: #2980b9;
        }
    </style>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">BMB Internet Service</div>
        <nav>
            <ul>
                <li><a href="#hero">Home</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#plans">Plans</a></li>
                <li><a href="#contact">Contact Us</a></li>
                <li><a href="#contact" id="loginBtn">Login</a></li>
            </ul>
        </nav>
    </header>

    <!-- Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h2>Login</h2>
            <p>Choose your role</p>
            <button id="loginClient">Login as Client</button>
            <button id="loginCollector">Login as Collector</button>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="hero-content">
            <h1>Experience the Fastest Internet with BMB</h1>
            <p>Reliable and Affordable Internet Plans Just for You</p>
            <a href="#plans" class="cta">Get Started</a>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <h2>Why Choose BMB?</h2>
        <div class="service-cards">
            <div class="service-card">
                <h3>Free Installation</h3>
                <p>Get started without any extra cost. Enjoy our free installation service.</p>
            </div>
            <div class="service-card">
                <h3>24/7 Customer Support</h3>
                <p>Our support team is available around the clock to assist you.</p>
            </div>
            <div class="service-card">
                <h3>High-Speed Internet</h3>
                <p>Enjoy uninterrupted streaming, gaming, and browsing with our high-speed plans.</p>
            </div>
        </div>
    </section>

    <!-- Fiber Plans Section -->
    <section class="fiber-plans" id="plans">
        <h2>Our Fiber Plans</h2>
        <div class="plan-cards">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "dbinternet";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT PlanID, Plan, MonthlyCost, Description FROM tblplan";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="plan-card">';
                    echo '<h3>' . $row["Plan"] . '</h3>';
                    echo '<p class="price">' . $row["MonthlyCost"] . ' pesos monthly</p>';
                    echo '<ul>';
                    echo '<li>' . $row["Description"] . '</li>';
                    echo '</ul>';
                    echo '<a href="registration.php" class="cta">Sign Up Now</a>';
                    echo '</div>';
                }
            } else {
                echo "No plans available.";
            }

            $conn->close();
            ?>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits" id="benefits">
        <h2>What You Get with BMB</h2>
        <div class="benefit-cards">
            <div class="benefit-card">
                <h3>Unlimited Data</h3>
                <p>No data caps. Enjoy unlimited browsing and streaming.</p>
            </div>
            <div class="benefit-card">
                <h3>No Hidden Fees</h3>
                <p>Transparent pricing with no surprise charges.</p>
            </div>
            <div class="benefit-card">
                <h3>Fast Installation</h3>
                <p>Get connected quickly with our efficient installation process.</p>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="call-to-action" id="call-to-action">
        <h2>Ready to Get Started?</h2>
        <a href="registration.php" class="cta">Sign Up for BMB Internet Service Today!</a>
    </section>


    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="company-info">
                <h3>Company Information</h3>
                <ul>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#privacy">Privacy Policy</a></li>
                    <li><a href="#terms">Terms of Service</a></li>
                </ul>
            </div>
            <div class="contact-info">
                <h3>Contact Information</h3>
                <ul>
                    <li>Email: info@bmbinternet.com</li>
                    <li>Phone: +123 456 7890</li>
                    <li>Address: 123 Internet Blvd, Tech City</li>
                </ul>
            </div>
            <div class="social-media">
                <h3>Follow Us</h3>
                <ul>
                    <li><a href="https://www.facebook.com/bmbinternet" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="https://twitter.com/bmbinternet" target="_blank"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="https://www.instagram.com/bmbinternet" target="_blank"><i class="fab fa-instagram"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 BMB Internet Service. All rights reserved.</p>
        </div>
    </footer>

    <!-- Smooth Scroll Script -->
    <script>
        document.querySelectorAll('nav a, .cta:not([href*="registration.php"])').forEach(anchor => {  
            anchor.addEventListener('click', function(e) {  
                e.preventDefault();  
                document.querySelector(this.getAttribute('href')).scrollIntoView({  
                    behavior: 'smooth'  
                });  
            });  
        });
        // Modal Script
        const loginBtn = document.getElementById('loginBtn');
        const loginModal = document.getElementById('loginModal');
        const loginClient = document.getElementById('loginClient');
        const loginCollector = document.getElementById('loginCollector');

        // Show modal when login button is clicked
        loginBtn.addEventListener('click', function() {
            loginModal.style.display = 'flex';
        });

        // Redirect to client login
        loginClient.addEventListener('click', function() {
            window.location.href = 'login.php';
        });

        // Redirect to collector login
        loginCollector.addEventListener('click', function() {
            window.location.href = 'collector_login.php';
        });

        // Close modal if clicking outside of it
        window.onclick = function(event) {
            if (event.target == loginModal) {
                loginModal.style.display = 'none';
            }
        }
    </script>

</body>
</html>
