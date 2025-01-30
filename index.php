<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Vanguard">
    <meta name="description" content="BMB Cell System">
    <meta name="keywords" content="BMB Cell Aurora, BMB Cell, Aurora">
    <link rel="icon" href="Images/logo.ico"/>
    <title>BMB Cell Aurora</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>
    <style>
        body {
            top: 0; 
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            background-color: rgb(243, 245, 250);
            scroll-behavior: smooth;
        }

        .back-to-top {
            position: fixed;
            bottom: 40px;
            left: 40px;
            width: 50px;
            height: 50px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 20px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
            z-index: 1000;
        }

        .back-to-top:hover {
            background-color: #0056b3;
            transform: scale(1.1);
        }

        /* Header Styles */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background-color: transparent;
            transition: background-color 0.3s ease;
        }

        header.scrolled {
            background-color:  #1e293b; /* Change to the desired color when scrolled */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 30px;
        }

        header .logo {
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
            box-shadow: 0 4px 10px rgba(13, 150, 241, 0.3); 
        }

        header nav .nav-links a.active {
            background-color: #2980b9;
            color: #fff;
        }

        /* Responsive Navigation Links */
        @media (max-width: 920px) {
            header nav .nav-links {
                margin-top: 0;
                text-align: center;
                flex-direction: column;
                position: absolute;
                top: 100%;
                right: 0;
                width: 100%;
                background-color: #333;
                padding: 10px 20px;
                display: none;
            }

            header nav .nav-links.active {
                display: flex;
            }

            header nav .nav-links li {
                margin: 10px 0;
                text-align: center;
            }

            header nav .menu-toggle {
                display: flex;
            }
        }

        header nav .menu-toggle {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            z-index: 11;
        }

        header nav .menu-toggle span {
            display: block;
            width: 25px;
            height: 3px;
            background-color: #fff;
            border-radius: 2px;
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
        @media (max-width: 920px) {
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
                background-color: #1e293b;
                padding: 10px 20px;
                z-index: 10;
            }

            header nav .nav-links.active {
                display: flex;
            }
        }
        /* Hero Section Styles */
        .hero {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            text-align: left;
            padding: 0 50px;
            color: #fff;
            overflow: hidden;
            background: url('Images/bgbg.jpg') no-repeat center center/cover;
        }

        .hero-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            animation: fadeIn 1.3s ease-in-out;
            margin-left: 150px;
        }


        /* Responsive Font Sizes with clamp() */
        .hero h1 {
            font-size: clamp(2rem, 4vw, 4rem);
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
            color: #38bdf8;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .hero p {
            font-size: clamp(1rem, 2.5vw, 1.5rem);
            font-weight: 400;
            margin-bottom: 30px;
            color: #e2e8f0;
            line-height: 1.5;
        }

        /* Hero Buttons Container */
        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 15px;
            margin-top: 60px;
        }

        /* Learn More Button Style */
        .learn-more {
            border-style: solid;
            border-radius: 8px;
            padding: 12px;
            font-size: 1.2rem;
            font-weight: 500;
            color: #e2e8f0;
            cursor: pointer;
            transition: color 0.3s ease;   
        }

        .learn-more:hover {
            text-decoration: none;
            background-color: #e2e8f0;
            color: #121212;
        }

        /* CTA Button Adjustments */
        .cta {
            padding: 15px 30px;
            background-color: #2563eb;
            color: #fff;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
        }

        .cta:hover {
            background-color: #1e40af;
            box-shadow: 0 6px 15px rgba(30, 64, 175, 0.5);
            transform: scale(1.05);
        }

        /* Responsive Hero Section */
        @media (max-width: 1024px) {
            .hero-content {
                margin-left: 20px; 
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding: 0 20px; 
            }

            .hero-content {
                margin-left: 0; 
                text-align: center; 
            }

            .hero-buttons {
                flex-direction: column;
                gap: 10px;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.2rem;
            }

            .cta {
                font-size: 1rem;
                padding: 10px 20px;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }
            .learn-more {
                padding: 9px;
                font-size: 1rem;
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Desktop Responsive Styles */
        @media (min-width: 920px) {

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
                background-color: #2563eb;
                color: #fff;
            }

            .menu-toggle {
                display: none !important;
            }

            .modal-content {
                width: 400px;
                padding: 30px;
            }

            .modal-content button {
                font-size: 16px;
                padding: 12px 20px;
            }
        }

        /* Partnership Section Styles */
        .partnership {
            background-color: rgb(243, 245, 250);
            text-align: center;
            position: relative;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .partnership h2 {
            font-size: 1rem;
            color: gray;
            font-weight: 700;
            letter-spacing: 1px;
            flex-wrap: wrap;
        }

        /* Marquee Container Styles */
        .marquee-container {
            max-width: 1200px;
            width: 100%; 
            margin: 0 auto;
            overflow: hidden;
            position: relative;
            height: 60px;
            background-color: rgba(0, 0, 0, 0);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Marquee Track Styles */
        .marquee-track {
            display: flex;
            gap: 120px;
            animation: marquee 20s linear infinite;
            align-items: center;
            justify-content: center;
        }

        .marquee-container:hover .marquee-track {
            animation-play-state: paused;
        }

        /* Reverse Direction for Bottom Marquee */
        .reverse .marquee-track {
            animation-direction: reverse;
        }

        /* Marquee Item Styles */
        .marquee-item {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            padding: 9px;
        }

        .marquee-item img {
            width: 100px;
            filter: grayscale(100%);
            filter: drop-shadow(2px 2px 5px rgba(0, 0, 0, 0.5));
            transition: filter 0.3s ease, transform 0.3s ease;
        }

        .marquee-item img:hover {
            filter: grayscale(0%);
            transform: scale(1.1);
        }

        /* Fade Overlay */
        .fade-overlay {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 50px;
            z-index: 1;
            pointer-events: none;
        }

        .fade-overlay.left {
            left: 0;
            background: linear-gradient(to right, rgba(243, 245, 250, 1), rgba(243, 245, 250, 0));
        }

        .fade-overlay.right {
            right: 0;
            background: linear-gradient(to left, rgba(243, 245, 250, 1), rgba(243, 245, 250, 0));
        }

        /* Animation */
        @keyframes marquee {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-20%);
            }
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .marquee-item img {
                width: 80px;
            }
        }

        @media (max-width: 480px) {
            .marquee-item img {
                width: 60px;
            }
        }

        /* Services Section Styles */
        .services{
            background-color: rgb(243, 245, 250);
            /* background: url('Images/bg.jpg') no-repeat center center/cover; */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 40px;
        }

        .services-container {
            display: flex;
            max-width: 1500px;
            height: 60%;
            width: 100%;
            background: white;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        /* Left Container */
        .left-container {
            flex: 1;
            padding: 50px;
            background: linear-gradient(135deg, #00aaff, #005f99);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: left;
            transform: translateX(-50px);
            opacity: 0;
            animation: fadeInLeft 1s ease-in-out forwards;
        }

        .left-container h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .left-container p {
            font-size: 16px;
            opacity: 0.9;
        }

        /* Right Container (Service Cards) */
        .right-container {
            flex: 2;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            padding: 40px;
        }

        .service-cards {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            opacity: 0;
            animation: fadeInUp 1s ease-in-out forwards;
        }

        .service-cards:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
        }

        .service-cards img {
            width: 24px;
            margin-bottom: 15px;
        }

        .service-cards h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: black;
        }

        .service-cards p {
            font-size: 14px;
            color: gray;
        }

        @keyframes fadeInLeft {
            from {
                transform: translateX(-50px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Small Screens (max-width: 480px) */
        @media (max-width: 480px) {

            .right-container {
                gap: 20px;
                padding: 10px;
            }
            .service-cards{
                padding: 10px;
                width: 80%;
                text-align: center;
            }

            .service-cards img {
                margin-bottom: 5px;
            }

            .service-cards h3 {
                font-size: 15px;
            }

            .service-cards p {
                font-size: 12px;
            }
        }

        @media (max-width: 640px) {
            .services-container {
                flex-direction: column-reverse; /* Moves the left-container to the top */
            }

            .left-container {
                display: none; /* Hides the left container */
            }

            .right-container {
                width: 100%;
                grid-template-columns: 1fr;
                padding: 20px;
            }
        }

        /* Tablets (min-width: 768px) and (max-width: 1023px) */
        @media (min-width: 768px) and (max-width: 1023px) {
            .container {
                flex-direction: column;
            }

            .left-container {
                padding: 40px;
                text-align: center;
            }

            .right-container {
                grid-template-columns: repeat(2, 1fr);
                padding: 30px;
            }
        }

        /* Desktop Screens (min-width: 1024px) */
        @media (min-width: 1024px) {
            .container {
                flex-direction: row;
            }

            .left-container {
                padding: 60px;
            }

            .right-container {
                grid-template-columns: repeat(2, 1fr);
                padding: 40px;
            }
        }

        /* Fiber Plans Section */
        .fiber-plans {
            width: auto;
            height: 100vh;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .fiber-plans .container {
            padding: 100px;
            background: linear-gradient(135deg, #00aaff, #005f99);
            border-radius: 12px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 1500px;
            width: 70%;
            text-align: center;
        }

        .fiber-plans h2 {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 40px;
            font-weight: 700;
            letter-spacing: 1px;
            flex-wrap: wrap;
        }

        .plan-cards {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-bottom: 50px;
        }

        .plan-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        .plan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
        }

        .plan-card h3 {
            font-size: 1.8rem;
            margin-bottom: 10px;
            color: #00aaff;
        }

        .plan-card .price {
            font-size: 1.5rem;
            color: black;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .plan-card ul {
            list-style: none;
            padding: 0;
            margin-bottom: 20px;
            text-align: center;
        }

        .plan-card ul li {
            font-size: 1rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: gray;
        }

        .plan-card ul li::before {
            content: "✔";
            margin-right: 10px;
            color: #00aaff;
            font-weight: bold;
        }

        .plan-card .cta {
            display: inline-block;
            text-decoration: none;
            background-color: #00aaff;
            color: #fff;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .plan-card .cta:hover {
            background-color: #0088cc;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .fiber-plans h2 {
                font-size: 1.1rem;
                margin: 10px;
            }

            .plan-card {
                width: auto;
                height: auto;
            }

            .plan-card h3 {
                font-size: 20px;
                margin: 10px;
            }

            .plan-card .price {
                font-size: 1rem;
                color: black;
                margin: 10px;
                font-weight: bold;
            }

            .plan-card ul li {
                font-size: 10px;
            }
            .plan-card .cta {
                padding: 5px 10px;
                font-size: 0.6rem;
                border-radius: 5px;
                font-weight: bold;
                transition: background-color 0.3s ease;
            }
        }

        @media (min-width: 481px) and (max-width: 767px) {
            .fiber-plans h2 {
                font-size: 1.2rem;
                margin: 10px;
            }

            .plan-card {
                width: auto;
                height: auto;
            }

            .plan-card h3 {
                font-size: auto;
                margin: auto;
            }

            .plan-card .price {
                font-size: 1rem;
                color: black;
                margin: 10px;
                font-weight: bold;
            }

            .plan-card ul li {
                font-size: 10px;
            }
            .plan-card .cta {
                padding: 5px 10px;
                font-size: 0.6rem;
                border-radius: 5px;
                font-weight: bold;
                transition: background-color 0.3s ease;
            }
        }

        /* Benefits Section Styles */
        .benefits {
            width: auto;
            height: 100vh;
            background-color: rgb(243, 245, 250);
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .benefits .benefits-container {
            max-width: 1200px;
            width: 100%;
            text-align: center;
        }

        .benefits h2 {
            font-size: 2.5em;
            margin-bottom: 40px;
            color: black;
            animation: fadeIn 1s ease-in-out;
        }

        .benefit-cards {
            display: flex;
            flex-direction: column;
            gap: 20px;
            opacity: 0;
            animation: fadeInUp 1.5s ease-in-out forwards;
            animation-delay: 0.5s;
        }

        .benefit-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            transform: scale(0.95);
        }

        .benefit-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.4);
        }

        .benefit-card h3 {
            font-size: 1.8em;
            margin-bottom: 10px;
            color: #00aaff;
        }

        .benefit-card p {
            font-size: 1.2em;
            color: #000;
            line-height: 1.5;
        }

        /* Fade-in Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .benefit-card {
                width: 85%;
            }

            .call-to-action .cta {
                font-size: 1.2em;
            }
        }

        @media (min-width: 768px) {
            .benefit-cards {
                flex-direction: row;
                justify-content: center;
                gap: 30px;
            }

            .benefit-card {
                flex: 1;
                max-width: 30%;
            }
        }

    /* Footer Styles */
    footer {
        background-color: #1f1f1f;
        color: #fff;
        padding: 40px 20px;
        min-height: 50vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .footer-content {
        max-width: 1200px;
        display: flex;
        flex-direction: column;
        gap: 40px;
        text-align: center;
        flex: 1;
        justify-content: center; /* Center the footer content vertically */
    }

    .footer-content div {
        flex: 1;
    }

    .footer-content h3 {
        font-size: 1.8em; /* Slightly larger for visibility */
        margin-bottom: 15px;
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
        padding: 10px 0;
        border-top: 1px solid #333; /* Adds separation */
    }

    /* Responsive Design */
    @media (min-width: 768px) {
        .footer-content {
            flex-direction: row;
            text-align: left;
        }

        .footer-content div {
            flex: 1;
            padding: 0 20px;
        }
    }
    /* Base animation styles */
    .fade-in,
    .fade-down {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }

    .fade-in.visible,
    .fade-down.visible {
        opacity: 1;
        transform: translateY(0);
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
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(5px); /* Subtle blur effect */
        animation: fadeIn 0.5s ease-in-out; /* Fade-in animation */
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .modal-content {
        background-color: #282c34; /* Matches theme color */
        padding: 30px;
        border-radius: 15px;
        width: 350px;
        text-align: center;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
        color: #ffffff;
        font-family: 'Arial', sans-serif;
        transform: translateY(-30px); /* Starting position */
        animation: slideDown 0.4s ease-out; /* Slide down animation */
    }

    @keyframes slideDown {
        from {
            transform: translateY(-30px);
        }
        to {
            transform: translateY(0);
        }
    }

    .modal-content h2 {
        font-size: 1.8rem;
        margin-bottom: 10px;
        color: #61dafb;
    }

    .modal-content p {
        font-size: 1rem;
        margin-bottom: 20px;
        color: #aaa;
    }

    .modal-content button {
        display: block;
        width: 100%;
        padding: 12px 0;
        margin: 10px 0;
        border: none;
        background: linear-gradient(45deg, #61dafb, #3498db); 
        color: white;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s ease-in-out;
    }

    .modal-content button:hover {
        background: linear-gradient(45deg, #3498db, #61dafb);
        transform: translateY(-2px); /* Subtle lift effect */
    }

    .modal-content button:active {
        transform: translateY(1px); /* Pressed effect */
    }

    .modal-content .close-btn {
        background-color: transparent;
        border: none;
        color: #aaa;
        font-size: 1.5rem;
        position: absolute;
        top: 15px;
        right: 20px;
        cursor: pointer;
        transition: color 0.3s ease-in-out;
    }

    .modal-content .close-btn:hover {
        color: #ffffff;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Initial state */
    [data-animate] {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }

    /* Animation triggered */
    [data-animate].animate {
        opacity: 1;
        transform: translateY(0);
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
                    <li><a href="#benefits">Benefits</a></li>
                    <li><a href="#footer">Contact Us</a></li>
                    <li><a id="loginBtn" class="btn-login">Login</a></li>
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
    <section class="hero" id="hero" class="fade-in" data-animate>
        <!-- Video Background -->
        <video autoplay muted loop playsinline class="hero-video">
            <source src="Images/4k.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <!-- Content Overlay -->
        <div class="hero-content" data-animate>
            <h1>Experience the Fastest Internet with BMB</h1>
            <p>Reliable and Affordable Internet Plans Just for You</p>
            <div class="hero-buttons">
                <a href="#plans" class="cta">
                    <i class="fas fa-rocket"></i> Get Started
                </a>
                <span class="learn-more">
                    <i class="fas fa-info-circle"></i> Learn More
                </span>
            </div>
        </div>
        
    </section>

    <button id="backToTop" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Partnership Section -->
    <section class="partnership" id="partners">
        <h2> IN PARTNERSHIP WITH</h2>
        <!-- Top Marquee: Right to Left -->
        <div class="marquee-container">
            <div class="fade-overlay left"></div>
            <div class="marquee-track">
                <a class="marquee-item"><img src="Images/Partners/Huaweiii.png" alt="Huawei"></a>
                <a class="marquee-item"><img src="Images/Partners/tplink.png" alt="TP-Link"></a>
                <a class="marquee-item"><img src="Images/Partners/Ubiquiti.png" alt="Ubiquiti"></a>
                <a class="marquee-item"><img src="Images/Partners/Vsolll.png" alt="V-SOL"></a>
                <a class="marquee-item"><img src="Images/Partners/Mercusys.png" alt="Mercusys"></a>
                <a class="marquee-item"><img src="Images/Partners/Mimosa.png" alt="Mimosa"></a>
                <a class="marquee-item"><img src="Images/Partners/Ruijie.png" alt="Ruijie"></a>
                <a class="marquee-item"><img src="Images/Partners/Tenda.png" alt="Tenda"></a>
                <a class="marquee-item"><img src="Images/Partners/Huaweiii.png" alt="Huawei"></a>
                <a class="marquee-item"><img src="Images/Partners/tplink.png" alt="TP-Link"></a>
                <a class="marquee-item"><img src="Images/Partners/Ubiquiti.png" alt="Ubiquiti"></a>
                <a class="marquee-item"><img src="Images/Partners/Vsolll.png" alt="V-SOL"></a>
                <a class="marquee-item"><img src="Images/Partners/Mercusys.png" alt="Mercusys"></a>
                <a class="marquee-item"><img src="Images/Partners/Mimosa.png" alt="Mimosa"></a>
                <a class="marquee-item"><img src="Images/Partners/Ruijie.png" alt="Ruijie"></a>
                <a class="marquee-item"><img src="Images/Partners/Tenda.png" alt="Tenda"></a>
            </div>
            <div class="fade-overlay right"></div>
        </div>

        <!-- Bottom Marquee: Left to Right -->
        <div class="marquee-container reverse">
            <div class="fade-overlay left"></div>
            <div class="marquee-track">
                <a href="https://www.huawei.com/en/" target="_blank" class="marquee-item"><img src="Images/Partners/Huaweiii.png" alt="Huawei"></a>
                <a class="marquee-item"><img src="Images/Partners/tplink.png" alt="TP-Link"></a>
                <a class="marquee-item"><img src="Images/Partners/Ubiquiti.png" alt="Ubiquiti"></a>
                <a class="marquee-item"><img src="Images/Partners/Vsolll.png" alt="V-SOL"></a>
                <a class="marquee-item"><img src="Images/Partners/Mercusys.png" alt="Mercusys"></a>
                <a class="marquee-item"><img src="Images/Partners/Mimosa.png" alt="Mimosa"></a>
                <a class="marquee-item"><img src="Images/Partners/Ruijie.png" alt="Ruijie"></a>
                <a class="marquee-item"><img src="Images/Partners/Tenda.png" alt="Tenda"></a>
                <a class="marquee-item"><img src="Images/Partners/Huaweiii.png" alt="Huawei"></a>
                <a class="marquee-item"><img src="Images/Partners/tplink.png" alt="TP-Link"></a>
                <a class="marquee-item"><img src="Images/Partners/Ubiquiti.png" alt="Ubiquiti"></a>
                <a class="marquee-item"><img src="Images/Partners/Vsolll.png" alt="V-SOL"></a>
                <a class="marquee-item"><img src="Images/Partners/Mercusys.png" alt="Mercusys"></a>
                <a class="marquee-item"><img src="Images/Partners/Mimosa.png" alt="Mimosa"></a>
                <a class="marquee-item"><img src="Images/Partners/Ruijie.png" alt="Ruijie"></a>
                <a class="marquee-item"><img src="Images/Partners/Tenda.png" alt="Tenda"></a>
            </div>
            <div class="fade-overlay right"></div>
        </div>
    </section>


    <!-- Services Section -->
    <section class="services" id="services" data-animate>
        <div class="services-container">
            <div class="left-container">
                <h2>Online Portal</h2>
                <p>Easily access your account in our online portal</p>
            </div>
            <div class="right-container">
                <div class="service-cards" data-animate class="fade-in">
                    <img src="Images/billing.png"  style="width:30px;" alt="Billing">
                    <h3>View Billing</h3>
                    <p>View your billing and payment history</p>
                </div>
                <div class="service-cards" data-animate class="fade-in">
                    <img src="Images/manage.png" style="width:30px;" alt="Manage">
                    <h3>Manage</h3>
                    <p>Manage your account with ease</p>
                </div>
                <div class="service-cards" data-animate class="fade-in">
                    <img src="Images/customer-support.png" style="width:30px;"alt="Customer Support">
                    <h3>Customer Support</h3>
                    <p>Get assistance whenever needed</p>
                </div>
                <div class="service-cards" data-animate class="fade-in">
                    <img src="Images/secured.png" style="width:30px;" alt="Secure">
                    <h3>Secure</h3>
                    <p>Secured database with encrypted protection</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fiber Plans Section -->
    <section class="fiber-plans" id="plans" data-animate="">
        <div class="container">
            <h2>Choose the Best Plan for You</h2>
            <div class="plan-cards">
            <?php
            include('db_connection.php');
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT PlanID, Plan, MonthlyCost, Description FROM tblplan";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="plan-card">';
                    echo '<h3>Fiber Plan</h3>';
                    echo '<p class="price">₱' . number_format($row["MonthlyCost"]) . ' / month</p>';
                    echo '<ul>';
                    echo '<li>' . $row["Description"] . '</li>';
                    echo '<li>Unlimited Internet</li>';
                    echo '<li>Free Modem Router</li>';
                    echo '</ul>';
                    echo '<a href="registration.php" class="cta">Apply Now</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>No plans available.</p>";
            }

            $conn->close();
            ?>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits" id="benefits" class="fade-down" data-animate>
        <div class="benefits-container">
            <h2>What You Get with BMB</h2>
            <div class="benefit-cards" data-animate>
                <div class="benefit-card" data-animate>
                    <h3>Unlimited Data</h3>
                    <p>No data caps. Enjoy unlimited browsing and streaming.</p>
                </div>
                <div class="benefit-card" data-animate>
                    <h3>No Hidden Fees</h3>
                    <p>Transparent pricing with no surprise charges.</p>
                </div>
                <div class="benefit-card" data-animate>
                    <h3>Fast Installation</h3>
                    <p>Get connected quickly with our efficient installation process.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer id="footer">
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
                    <li>Email: bmbcell@gmail.com</li>
                    <li>Phone: coming soon</li>
                    <li>Address: Aurora, Isabela</li>
                </ul>
            </div>
            <div class="social-media">
                <h3>Follow Us</h3>
                <ul>
                    <li><a href="https://www.facebook.com/bmb.cell.5" target="_blank"><i class="fab fa-facebook-f"></i></a> BMB Cell and Computer Shop</li>
                    <li><a href="" target="_blank"><i class="fab fa-twitter"></i></a> @bmbcell</li>
                    <li><a href="" target="_blank"><i class="fab fa-instagram"></i></a> @bmbcell</li>
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
        window.addEventListener('scroll', function () {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

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
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Animation on Scroll
        const fadeElements = document.querySelectorAll('.fade-in, .fade-down');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        fadeElements.forEach(el => observer.observe(el));

        // Modal Script
        const loginBtn = document.getElementById('loginBtn');
        const loginModal = document.getElementById('loginModal');
        const loginClient = document.getElementById('loginClient');
        const loginCollector = document.getElementById('loginCollector');

        loginBtn.addEventListener('click', function () {
            loginModal.style.display = 'flex';
        });

        loginClient.addEventListener('click', function () {
            window.location.href = 'login.php';
        });

        loginCollector.addEventListener('click', function () {
            window.location.href = 'collector_login.php';
        });

        window.onclick = function (event) {
            if (event.target == loginModal) {
                loginModal.style.display = 'none';
            }
        };

        document.addEventListener("DOMContentLoaded", () => {
            const animateElements = document.querySelectorAll("[data-animate]");

            // Check if element is in the viewport
            const isInViewport = (element) => {
                const rect = element.getBoundingClientRect();
                return rect.top <= window.innerHeight * 0.9; // Trigger animation when 90% visible
            };

            // Apply animation to elements in the viewport
            const handleScroll = () => {
                animateElements.forEach((el) => {
                    if (isInViewport(el)) {
                        el.classList.add("animate"); // Trigger animation
                    }
                });
            };

            window.addEventListener("scroll", handleScroll);
            handleScroll(); // Trigger on initial load
        });

        document.addEventListener("DOMContentLoaded", function () {
            let backToTopButton = document.getElementById("backToTop");

            // Show the button when scrolling down 200px
            window.addEventListener("scroll", function () {
                if (window.scrollY > 50) {
                    backToTopButton.style.display = "flex";
                } else {
                    backToTopButton.style.display = "none";
                }
            });

            // Scroll to top when clicked
            backToTopButton.addEventListener("click", function () {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        });

    </script>
</body>
</html>
