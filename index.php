<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMB Internet Service</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background-color: #121212;
            scroll-behavior: smooth;
        }

        /* Header Styles */
        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            position: relative;
            z-index: 10;
            
        }

        header .logo {
            font-size: 24px;
            font-weight: bold;
            color: #00aaff;
        }

        header nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Navigation Link Styles */
        header nav .nav-links li {
            list-style: none;
        }

        header nav .nav-links a {
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            padding: 5px 10px;
            transition: color 0.3s ease, background-color 0.3s ease;
            border-radius: 5px;
        }

        header nav .nav-links a:hover {
            background-color: #3498db;
            color: #fff;
        }

        /* Active Link Highlight */
        header nav .nav-links a.active {
            background-color: #2980b9;
            color: #fff;
        }

        /* Responsive Navigation Links */
        @media (max-width: 819px) {
            header nav .nav-links{
                margin-top: 0px;
                text-align: center;
            }
            header nav .nav-links li {
                margin: 10px 0;
                text-align: center;
            }
        }

        /* Menu Toggle */
        header nav .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            margin-left: auto;
            z-index: 11;
        }

        header nav .menu-toggle span {
            display: block;
            width: 25px;
            height: 3px;
            background-color: #fff;
            border-radius: 2px;
        }

        /* Responsive Design for Menu Toggle */
        @media (max-width: 819px) {
            header nav .menu-toggle {
                display: flex;
            }

            header nav .nav-links {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                right: 0;
                width: 100%;
                background-color: #333;
                padding: 10px 20px;
                z-index: 10;
            }

            header nav .nav-links.active {
                display: flex;
            }
        }

        /* Hero Section Styles */
        .hero {
            height: 100vh;
            background: url('Images/hero-background.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: relative;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3em;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.5em;
            margin-bottom: 40px;
        }

        .cta {
            padding: 10px 20px;
            background-color: #00aaff;
            color: #fff;
            text-decoration: none;
            font-size: 1.2em;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .cta:hover {
            background-color: #0088cc;
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

        /* Desktop Responsive Styles */
        @media (min-width: 820px) {

            header .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px 40px;
            }

            header .logo {
                font-size: 28px;
            }

            header nav {
                
                gap: 40px;
            }

            header nav .nav-links li {
                display: inline;
                margin: 0;
            }

            header nav .nav-links a {
                padding: 10px 20px;
                font-size: 16px;
            }

            header nav .nav-links a:hover,
            header nav .nav-links a.active {
                background-color: #2980b9;
                color: #fff;
            }

            .menu-toggle {
                display: none !important;
            }

            /* Modal for Desktop */
            .modal-content {
                width: 400px;
                padding: 30px;
            }

            .modal-content button {
                font-size: 16px;
                padding: 12px 20px;
            }
        }

        /* Services Section Styles */
.services {
    padding: 60px 20px;
    background-color: #1f1f1f;
    text-align: center;
}

.services h2 {
    font-size: 2.5em;
    margin-bottom: 40px;
    color: #fff;
}

.service-cards {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.service-card {
    background-color: #2c2c2c;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.service-card h3 {
    font-size: 1.8em;
    margin-bottom: 10px;
    color: #00aaff;
}

.service-card p {
    font-size: 1.2em;
    color: #fff;
}



/* Media Queries for Responsive Design */
@media (min-width: 768px) {
    .service-cards {
        flex-direction: row;
        justify-content: space-between;
    }

    .service-card {
        flex: 1;
        margin: 0 10px;
    }
}

/* Fiber Plans Section Styles */
.fiber-plans {
    padding: 60px 20px;
    background-color: #121212;
    text-align: center;
}

.fiber-plans h2 {
    font-size: 2.5em;
    margin-bottom: 40px;
    color: #fff;
}

.plan-cards {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.plan-card {
    background-color: #1f1f1f;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.plan-card h3 {
    font-size: 1.8em;
    margin-bottom: 10px;
    color: #00aaff;
}

.plan-card .price {
    font-size: 1.5em;
    margin-bottom: 20px;
    color: #00ff99;
}

.plan-card ul {
    list-style: none;
    padding: 0;
    margin-bottom: 20px;
    color: #fff;
}

.plan-card ul li {
    font-size: 1.2em;
    margin-bottom: 10px;
}

.plan-card .cta {
    padding: 10px 20px;
    background-color: #00aaff;
    color: #fff;
    text-decoration: none;
    font-size: 1.2em;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.plan-card .cta:hover {
    background-color: #0088cc;
}

/* Media Queries for Responsive Design */
@media (min-width: 768px) {
    .plan-cards {
        flex-direction: row;
        justify-content: space-between;
    }

    .plan-card {
        flex: 1;
        margin: 0 10px;
    }
}


/* Benefits Section Styles */
.benefits {
    padding: 60px 20px;
    background-color: #1f1f1f;
    text-align: center;
}

.benefits h2 {
    font-size: 2.5em;
    margin-bottom: 40px;
    color: #fff;
}

.benefit-cards {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.benefit-card {
    background-color: #2c2c2c;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.benefit-card h3 {
    font-size: 1.8em;
    margin-bottom: 10px;
    color: #00aaff;
}

.benefit-card p {
    font-size: 1.2em;
    color: #fff;
}


/* Media Queries for Responsive Design */
@media (min-width: 768px) {
    .benefit-cards, .testimonial-cards {
        flex-direction: row;
        justify-content: space-between;
    }

    .benefit-card, .testimonial-card {
        flex: 1;
        margin: 0 10px;
    }
}

/* Call to Action Section Styles */
.call-to-action {
    padding: 60px 20px;
    background-color: #1f1f1f;
    text-align: center;
}

.call-to-action h2 {
    font-size: 2.5em;
    margin-bottom: 20px;
    color: #fff;
}

.call-to-action .cta {
    padding: 15px 30px;
    background-color: #00aaff;
    color: #fff;
    text-decoration: none;
    font-size: 1.2em;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.call-to-action .cta:hover {
    background-color: #0088cc;
}

/* Footer Styles */
footer {
    background-color: #121212;
    color: #fff;
    padding: 40px 20px;
}

.footer-content {
    display: flex;
    flex-direction: column;
    gap: 40px;
    text-align: center;
}

.footer-content div {
    flex: 1;
}

.footer-content h3 {
    font-size: 1.5em;
    margin-bottom: 10px;
}

.footer-content ul {
    list-style: none;
    padding: 0;
}

.footer-content ul li {
    margin-bottom: 10px;
}

.footer-content ul li a {
    color: #00aaff;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-content ul li a:hover {
    color: #0088cc;
}

.footer-bottom {
    text-align: center;
    margin-top: 20px;
    font-size: 0.9em;
}

/* Media Queries for Responsive Design */
@media (min-width: 768px) {
    .footer-content {
        flex-direction: row;
        text-align: left;
    }

    .footer-content div {
        text-align: left;
    }
}
    </style>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <div class="logo">BMB Aurora</div>
            <nav>
                <ul class="nav-links">
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#plans">Plans</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                    <li><a href="#contact" id="loginBtn" class="btn-login">Login</a></li>
                </ul>
                <div class="menu-toggle" id="mobileMenu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </nav>
        </div>
    </header>

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
                    echo '<a href="registration.php" class="cta">Register</a>';
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
        <a href="registration.php" class="cta">Sign Up Now!</a>
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
                    <li><a href="https://www.facebook.com/bmbinternet" target="_blank"><i class="fab fa-facebook-f"></i></a> BMB Internet Service Aurora</li>
                    <li><a href="https://twitter.com/bmbinternet" target="_blank"><i class="fab fa-twitter"></i></a> @bmbinternet</li>
                    <li><a href="https://www.instagram.com/bmbinternet" target="_blank"><i class="fab fa-instagram"></i></a> @bmbinternet</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 BMB Cell and Computer Shop. All rights reserved.</p>
        </div>
    </footer>
    
    <!-- Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <h2>Login</h2>
            <p>Choose your role</p>
            <button id="loginClient">Login as Client</button>
            <button id="loginCollector">Login as Collector</button>
        </div>
    </div>


    <!-- Smooth Scroll Script -->
    <script>
        const mobileMenu = document.getElementById("mobileMenu");
        const navLinks = document.querySelector(".nav-links");

        mobileMenu.addEventListener("click", () => {
            navLinks.classList.toggle("active");
        });
        
        document.querySelectorAll('.nav-links a').forEach(link => {
            link.addEventListener('click', () => {
                if (navLinks.classList.contains('active')) {
                    navLinks.classList.remove('active');
                }
            });
        });

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
