<?php

// Trả về mã lỗi 403
http_response_code(403);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chậm Lại Nào!</title>
    <style>
        body {
            background: linear-gradient(135deg, #6a0dad, #4b0082);
            color: #ffffff;
            font-family: 'Arial', -apple-system, BlinkMacSystemFont, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            animation: gradientShift 10s ease infinite;
        }

        .container {
            text-align: center;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            animation: slideIn 1s ease-out;
            max-width: 600px;
            color: #333;
        }

        h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #d32f2f;
            text-shadow: 0 0 5px rgba(211, 47, 47, 0.2);
            animation: gentlePulse 2s ease-in-out infinite;
        }

        p {
            font-size: 1.3rem;
            color: #555;
            margin-bottom: 1.5rem;
            line-height: 1.6;
            animation: fadeIn 1.2s ease-out;
        }

        #countdown {
            font-size: 3rem;
            font-weight: 700;
            margin: 1.5rem 0;
            color: #ff69b4;
            text-shadow: 0 0 6px rgba(255, 105, 180, 0.3);
            animation: scaleBounce 0.5s ease-in-out alternate infinite;
        }

        #reloadBtn {
            display: none;
            padding: 15px 30px;
            font-size: 1.2rem;
            font-weight: 600;
            background: linear-gradient(45deg, #ff69b4, #ff85c0);
            color: #ffffff;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(255, 105, 180, 0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        #reloadBtn:hover {
            transform: translateY(-4px);
            box-shadow: 0 7px 25px rgba(255, 105, 180, 0.7);
            background: linear-gradient(45deg, #ff85c0, #ff69b4);
        }

        #reloadBtn:active {
            transform: translateY(0);
            box-shadow: 0 3px 15px rgba(255, 105, 180, 0.3);
        }

        /* Animations */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes gentlePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        @keyframes scaleBounce {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Particle system */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 5px;
            height: 5px;
            background: rgba(255, 105, 180, 0.6);
            border-radius: 50%;
            animation: particleFloat 4s linear infinite;
            box-shadow: 0 0 6px rgba(255, 105, 180, 0.3);
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>
    <div class="container">
        <h1>Hãy Chậm Lại Nào!</h1>
        <p id="message">Xin lỗi, bạn đang thao tác quá nhanh. Hãy chậm lại và thử lại sau <span id="countdown">3</span> giây nhé!</p>
        <button id="reloadBtn" onclick="location.reload()">🔄 Bắt Đầu Lại Ngay!</button>
    </div>
    <script src="../assets/js/dev.js"></script>
    <script>
        // Countdown logic
        let seconds = 3;
        const countdownEl = document.getElementById("countdown");
        const reloadBtn = document.getElementById("reloadBtn");
        const message = document.getElementById("message");

        const timer = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(timer);
                countdownEl.style.display = "none";
                message.style.display = "none";
                reloadBtn.style.display = "inline-block";
                reloadBtn.style.animation = "fadeIn 0.5s ease-out";
                message.textContent = "Nhấn nút để tiếp tục nào!";
                message.style.display = "block";
            }
        }, 1000);

        // Particle effect
        function createParticle() {
            const particle = document.createElement("div");
            particle.className = "particle";
            particle.style.left = Math.random() * 100 + "vw";
            particle.style.top = Math.random() * 100 + "vh";
            particle.style.animationDuration = `${Math.random() * 3 + 2}s`;
            particle.style.transform = `scale(${Math.random() * 0.5 + 0.5})`;
            document.getElementById("particles").appendChild(particle);

            setTimeout(() => particle.remove(), 3000);
        }

        setInterval(createParticle, 150);

        // Particle animation
        const styleSheet = document.createElement("style");
        styleSheet.textContent = `
            @keyframes particleFloat {
                0% { transform: translateY(0) scale(1); opacity: 0.6; }
                100% { transform: translateY(-100vh) scale(0.4); opacity: 0; }
            }
        `;
        document.head.appendChild(styleSheet);
    </script>
</body>
</html>
