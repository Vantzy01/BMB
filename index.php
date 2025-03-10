<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Vanguard">
    <meta name="description" content="BMB Cell System">
    <meta name="keywords" content="BMB Cell Aurora, BMB Cell, Aurora">
    <link rel="icon" href="Images/logo.ico" />
    <title>BMB Cell Aurora</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</head>

<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        color: #fff;
        background-color: #121212;
        scroll-behavior: smooth;
    }

    .back-to-top {
        position: fixed;
        bottom: 20px;
        left: 20px;
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

    @keyframes fadeDown {
        0% {
            opacity: 0;
            transform: translateY(-30px) scale(0.5);
        }

        100% {
            opacity: 100;
            transform: translateY(0px) scale(1);
        }
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
        background-color: #1e293b;
        /* Change to the desired color when scrolled */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }

    header .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 30px 30px;
        animation: fadeDown 0.7s ease-in-out forwards;
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
        header {
            background-color: #1e293b;
            /* Change to the desired color when scrolled */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

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
            animation: fadeInDown;
            animation-duration: 0.5s;
        }

        header nav .nav-links.active {
            padding: 10px 0;
            margin: 0;
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
        height: 100vh;
        background: url('Images/hero.jpg') no-repeat center center/cover;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        text-align: left;
        padding: 0 50px;
        position: relative;
        color: #fff;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 0;
    }

    .hero-content {
        position: relative;
        z-index: 1;
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
        margin-top: 20px;
    }

    /* Learn More Button Style */
    .learn-more {
        font-size: 1.2rem;
        font-weight: 500;
        color: #e2e8f0;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .learn-more:hover {
        color: #38bdf8;
        text-decoration: underline;
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
            background-color: #2980b9;
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
        background-color: #1f1f1f;
        text-align: center;
        position: relative;
        justify-content: center;
        align-items: center;
        padding: 20px;
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

        transition: filter 0.3s ease, transform 0.3s ease;
    }

    .marquee-item img:hover {
        filter: grayscale(0%);
        filter: drop-shadow(3px 3px 5px rgba(220, 230, 229, 0.5));
        transform: scale(1.3);
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
        background: linear-gradient(to right, rgba(31, 31, 31, 1), rgba(31, 31, 31, 0));
    }

    .fade-overlay.right {
        right: 0;
        background: linear-gradient(to left, rgba(31, 31, 31, 1), rgba(31, 31, 31, 0));
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
            width: 90px;
        }

        .marquee-track {
            gap: 30px;
        }
    }


    /* Services Section Styles */
    .services {
        height: 100vh;
        background-color: #1f1f1f;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 60px 60px;
        color: #fff;
        overflow: hidden;
        position: relative;
    }

    .services h2 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 40px;
        color: #38bdf8;
        text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        animation: fadeInDown 1.5s ease-in-out;
    }

    /* Service Cards Layout */
    .service-cards {
        display: flex;
        flex-wrap: wrap;
        /* Ensures cards stack on smaller screens */
        gap: 30px;
        width: 100%;
        /* Full width */
        max-width: 1200px;
        /* Center content on desktop */
        justify-content: center;
        animation: fadeInUp 2s ease-in-out;
    }

    .service-card {
        background-color: #2c2c2c;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        text-align: left;
        width: 100%;
        /* Full width for mobile */
        max-width: 350px;
        /* Restrict card size for desktop */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .service-card:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
    }

    .service-card h3 {
        font-size: 1.5rem;
        /* Adapted size for all screens */
        margin-bottom: 10px;
        color: #00aaff;
    }

    .service-card p {
        font-size: 1rem;
        line-height: 1.6;
        color: #e2e8f0;
    }

    /* Responsive Design Services Section*/

    /* For small mobile screens (360px width) */
    @media (max-width: 480px) {
        .services h2 {
            font-size: 1.4rem;
            margin-bottom: 30px;
        }

        .service-card {
            padding: 15px;
            max-width: 100%;
        }

        .service-card h3 {
            font-size: 1.3rem;
        }

        .service-card p {
            font-size: 0.9rem;
        }
    }

    /* For tablets and smaller laptops */
    @media (min-width: 768px) and (max-width: 1023px) {
        .services h2 {
            font-size: 1.8rem;
            /* Slightly smaller font */
        }

        .service-cards {
            gap: 20px;
            /* Reduced gap for smaller screens */
        }

        .service-card {
            max-width: 300px;
            /* Slightly smaller cards */
        }
    }

    /* For larger desktops */
    @media (min-width: 1024px) {
        .services h2 {
            font-size: 3.5rem;
            /* Larger header for big screens */
            margin-bottom: 60px;
        }

        .service-cards {
            gap: 30px;
            /* Extra spacing between cards */
        }

        .service-card {
            padding: 30px;
            /* More spacious cards */
            max-width: 400px;
            /* Larger card size */
        }

        .service-card h3 {
            font-size: 1.8rem;
        }

        .service-card p {
            font-size: 1.1rem;
        }
    }

    /* Keyframe Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }


    /* Fiber Plans Section Styles */
    .fiber-plans {
        padding: 60px 20px;
        background-color: #121212;
        text-align: center;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .fiber-plans h2 {
        font-size: 2.5em;
        margin-bottom: 40px;
        color: #fff;
        animation: fadeIn 1s ease-in-out;
    }

    .plan-cards {
        display: flex;
        flex-wrap: no wrap;
        justify-content: center;
        gap: 20px;
        opacity: 0;
        animation: fadeIn 1.5s ease-in-out forwards;
        animation-delay: 0.5s;
    }

    .plan-card {
        background: linear-gradient(145deg, #1e1e1e, #262626);
        border-radius: 15px;
        padding: 30px;
        width: 90%;
        max-width: 300px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.5);
        transform: scale(0.95);
        transition: all 0.3s ease-in-out;
        animation: fadeInUp 1s ease-in-out forwards;
    }

    .plan-card:hover {
        transform: scale(1.02);
        box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.6);
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
        text-align: left;
    }

    .plan-card ul li {
        font-size: 1em;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .plan-card ul li::before {
        content: "✔";
        color: #00ff99;
        font-size: 1.2em;
    }

    .plan-card .cta {
        padding: 10px 20px;
        background-color: #00aaff;
        color: #fff;
        text-decoration: none;
        font-size: 1.2em;
        border-radius: 5px;
        display: inline-block;
        transition: background-color 0.3s ease-in-out;
    }

    .plan-card .cta:hover {
        background-color: #0088cc;
    }

    /* Fade-in Animation */
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

    /* Media Queries for Responsive Design */
    @media (max-width: 480px) {
        .plan-card {
            max-width: 100%;
            padding: 20px;
        }

        .plan-cards {
            flex-wrap: wrap;
        }

        .fiber-plans h2 {
            font-size: 2em;
        }
    }

    @media (max-width: 768px) {
        .plan-cards {
            flex-wrap: wrap;
        }
    }

    @media (min-width: 768px) and (max-width: 1023px) {
        .plan-card {
            max-width: 45%;
        }

        .plan-cards {
            flex-wrap: wrap;
        }
    }

    @media (min-width: 1024px) {
        .plan-card {
            max-width: 30%;
        }

    }


    /* Benefits Section Styles */
    .benefits {
        padding: 60px 20px;
        background-color: #1f1f1f;
        text-align: center;
        min-height: 100vh;
        /* Full screen height */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .benefits h2 {
        font-size: 2.5em;
        margin-bottom: 40px;
        color: #fff;
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
        background-color: #2c2c2c;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
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
        color: #fff;
        line-height: 1.5;
    }

    /* Call to Action Section Styles */
    .call-to-action {
        padding: 60px 20px;
        background-color: #121212;
        text-align: center;
        min-height: 100vh;
        /* Full screen height */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .call-to-action h2 {
        font-size: 2.5em;
        margin-bottom: 20px;
        color: #fff;
        animation: fadeIn 1s ease-in-out;
    }

    .call-to-action .cta {
        padding: 15px 30px;
        background-color: #00aaff;
        color: #fff;
        text-decoration: none;
        font-size: 1.5em;
        border-radius: 5px;
        transition: background-color 0.3s, transform 0.3s ease;
        animation: fadeInUp 1.5s ease-in-out forwards;
    }

    .call-to-action .cta:hover {
        background-color: #0088cc;
        transform: scale(1.05);
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
        /* Ensure the footer takes up at least half the screen height */
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        /* Spreads the content evenly */
    }

    .footer-content {
        max-width: 1200px;
        display: flex;
        flex-direction: column;
        align-self: center;

        gap: 40px;
        text-align: center;
        flex: 1;
        justify-content: center;
        /* Center the footer content vertically */
    }

    .footer-content div {
        flex: 1;
    }

    .footer-content h3 {
        font-size: 1.8em;
        /* Slightly larger for visibility */
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
        border-top: 1px solid #333;
        /* Adds separation */
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
        backdrop-filter: blur(5px);
        /* Subtle blur effect */
        animation: fadeIn 0.5s ease-in-out;
        /* Fade-in animation */
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
        background-color: #282c34;
        /* Matches theme color */
        padding: 30px;
        border-radius: 15px;
        width: 350px;
        text-align: center;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.6);
        color: #ffffff;
        font-family: 'Arial', sans-serif;
        transform: translateY(-30px);
        /* Starting position */
        animation: slideDown 0.4s ease-out;
        /* Slide down animation */
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
        /* Accent color from your theme */
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
        /* Gradient with theme accent colors */
        color: white;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s ease-in-out;
    }

    .modal-content button:hover {
        background: linear-gradient(45deg, #3498db, #61dafb);
        transform: translateY(-2px);
        /* Subtle lift effect */
    }

    .modal-content button:active {
        transform: translateY(1px);
        /* Pressed effect */
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
                    <span class="span1"></span>
                    <span class="span2"></span>
                    <span class="span3"></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="hero" class="fade-in" data-animate>
        <div class="hero-content" data-animate>
            <h1>Experience the Fastest Internet with BMB</h1>
            <p>Reliable and Affordable Internet Plans Just for You</p>
            <div class="hero-buttons">
                <a href="#plans" class="cta">Get Started</a>
                <span class="learn-more">Learn More</span>
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
    <section class="services" id="services">
        <h2>Why Choose BMB?</h2>
        <div class="service-cards">
            <div class="service-card" data-animate>
                <h3>Free Installation</h3>
                <p>Get started without any extra cost. Enjoy our free installation service.</p>
            </div>
            <div class="service-card" data-animate>
                <h3>24/7 Customer Support</h3>
                <p>Our support team is available around the clock to assist you.</p>
            </div>
            <div class="service-card" data-animate>
                <h3>High-Speed Internet</h3>
                <p>Enjoy uninterrupted streaming, gaming, and browsing with our high-speed plans.</p>
            </div>
        </div>
    </section>

    <!-- Fiber Plans Section -->
    <section class="fiber-plans" id="plans" data-animate>
        <h2>Choose the Best Plan for You</h2>
        <div class="plan-cards">
            <?php
            include('db_connection.php');
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT PlanID, Plan, MonthlyCost, Description FROM tblplan WHERE Status='1'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $highlighted = true; // Flag to highlight the middle plan
                while ($row = $result->fetch_assoc()) {
                    $highlightClass = $highlighted ? 'highlight' : '';
                    echo '<div class="plan-card ' . $highlightClass . '" data-animate>';
                    echo '<h3>FIBER PLAN</h3>';
                    echo '<p class="price">₱' . number_format($row["MonthlyCost"]) . ' / month</p>';
                    echo '<ul>';
                    echo '<li>' . $row["Description"] . '</li>';
                    echo '<li>Unlimited Internet</li>';
                    echo '<li>Free Modem Router</li>';
                    echo '</ul>';
                    echo '<a href="registration.php" class="cta">Apply Now</a>';
                    echo '</div>';
                    $highlighted = false;
                }
            } else {
                echo "<p>No plans available.</p>";
            }

            $conn->close();
            ?>
        </div>
    </section>


    <!-- Benefits Section -->
    <section class="benefits" id="benefits" class="fade-down" data-animate>
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
    </section>

    <!-- Call to Action Section -->
    <section class="call-to-action" id="call-to-action" class="fade-in">
        <h2>Ready to Get Started?</h2>
        <a href="registration.php" class="cta">Sign Up Now!</a>
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
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        const mobileMenu = document.getElementById("mobileMenu");
        const navLinks = document.querySelector(".nav-links");
        const menuToggle = document.querySelector(".menu-toggle");

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

        document.querySelectorAll('.menu-toggle').forEach(link => {
            link.addEventListener('click', () => {
                if (menuToggle.classList.contains('active')) {
                    menuToggle.classList.remove('active');
                } else {
                    menuToggle.classList.add("active");
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

        // Animation on Scroll
        const fadeElements = document.querySelectorAll('.fade-in, .fade-down');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });

        fadeElements.forEach(el => observer.observe(el));

        // Modal Script
        const loginBtn = document.getElementById('loginBtn');
        const loginModal = document.getElementById('loginModal');
        const loginClient = document.getElementById('loginClient');
        const loginCollector = document.getElementById('loginCollector');

        loginBtn.addEventListener('click', function() {
            loginModal.style.display = 'flex';
        });

        loginClient.addEventListener('click', function() {
            window.location.href = 'login.php';
        });

        loginCollector.addEventListener('click', function() {
            window.location.href = 'collector_login.php';
        });

        window.onclick = function(event) {
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

        document.addEventListener("DOMContentLoaded", function() {
            let backToTopButton = document.getElementById("backToTop");

            // Show the button when scrolling down 200px
            window.addEventListener("scroll", function() {
                if (window.scrollY > 50) {
                    backToTopButton.style.display = "flex";
                } else {
                    backToTopButton.style.display = "none";
                }
            });

            // Scroll to top when clicked
            backToTopButton.addEventListener("click", function() {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth"
                });
            });
        });
    </script>
</body>

</html>