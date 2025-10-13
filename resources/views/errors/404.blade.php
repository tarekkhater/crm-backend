<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Lost in the Void</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle at center, #1a1a3d 0%, #000000 100%);
            font-family: 'Orbitron', sans-serif;
            overflow: hidden;
            perspective: 1000px;
        }

        .container {
            text-align: center;
            position: relative;
            z-index: 1;
            color: #fff;
        }

        .error-code {
            font-size: 120px;
            font-weight: 700;
            transform-style: preserve-3d;
            animation: rotate3D 10s linear infinite;
            text-shadow: 0 0 20px rgba(0, 255, 255, 0.7), 0 0 40px rgba(0, 255, 255, 0.3);
        }

        .message {
            font-size: 24px;
            margin: 20px 0;
            position: relative;
            animation: glitch 2s linear infinite;
        }

        .message::before,
        .message::after {
            content: attr(data-text);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            color: #0ff;
            opacity: 0.8;
        }

        .message::before {
            animation: glitch-top 1s linear infinite;
            clip-path: polygon(0 0, 100% 0, 100% 33%, 0 33%);
            -webkit-clip-path: polygon(0 0, 100% 0, 100% 33%, 0 33%);
        }

        .message::after {
            animation: glitch-bottom 1.5s linear infinite;
            clip-path: polygon(0 67%, 100% 67%, 100% 100%, 0 100%);
            -webkit-clip-path: polygon(0 67%, 100% 67%, 100% 100%, 0 100%);
        }

        .stars {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .star {
            position: absolute;
            background: #fff;
            border-radius: 50%;
            opacity: 0.7;
            animation: moveStar 20s linear infinite;
        }

        .star:nth-child(1) { width: 2px; height: 2px; top: 10%; left: 20%; animation-delay: 0s; }
        .star:nth-child(2) { width: 3px; height: 3px; top: 30%; left: 50%; animation-delay: 2s; }
        .star:nth-child(3) { width: 1px; height: 1px; top: 50%; left: 70%; animation-delay: 4s; }
        .star:nth-child(4) { width: 2px; height: 2px; top: 70%; left: 30%; animation-delay: 6s; }
        .star:nth-child(5) { width: 3px; height: 3px; top: 20%; left: 80%; animation-delay: 8s; }
        .star:nth-child(6) { width: 1px; height: 1px; top: 80%; left: 10%; animation-delay: 10s; }

        @keyframes rotate3D {
            0% { transform: rotateY(0deg) rotateX(0deg); }
            100% { transform: rotateY(360deg) rotateX(360deg); }
        }

        @keyframes glitch {
            2% { transform: translate(2px, -2px); }
            4% { transform: translate(-2px, 2px); }
            60% { transform: translate(0, 0); }
        }

        @keyframes glitch-top {
            2% { transform: translate(2px, -2px); }
            4% { transform: translate(-2px, 2px); }
            60% { transform: translate(0, 0); }
        }

        @keyframes glitch-bottom {
            2% { transform: translate(-2px, 2px); }
            4% { transform: translate(2px, -2px); }
            60% { transform: translate(0, 0); }
        }

        @keyframes moveStar {
            0% { transform: translateZ(0); opacity: 0.7; }
            50% { transform: translateZ(200px); opacity: 0.3; }
            100% { transform: translateZ(0); opacity: 0.7; }
        }

        @media (max-width: 600px) {
            .error-code {
                font-size: 80px;
            }

            .message {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="stars">
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
        <div class="star"></div>
    </div>
    <div class="container">
        <div class="error-code">404</div>
        <div class="message" data-text="Lost in the Digital Void!">Lost in the Digital Void!</div>
        <div class="message" data-text="The page you seek is adrift in cyberspace.">The page you seek is adrift in cyberspace.</div>
    </div>
    <script>
        // Optional: Add subtle mouse movement interaction for 3D effect
        const errorCode = document.querySelector('.error-code');
        document.addEventListener('mousemove', (e) => {
            const xAxis = (window.innerWidth / 2 - e.pageX) / 25;
            const yAxis = (window.innerHeight / 2 - e.pageY) / 25;
            errorCode.style.transform = `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`;
        });
    </script>
</body>
</html>