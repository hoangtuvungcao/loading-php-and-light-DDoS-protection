<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$blockedIpFile = 'blocked_ips.txt';

// Lấy IP
$ip = $_SERVER['REMOTE_ADDR'];

// Phân biệt http/https kể cả dùng Cloudflare
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'] . '://';
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
                $_SERVER['SERVER_PORT'] == 443 ? "https://" : "http://";
}

// Lưu URL hiện tại
$main_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

// Anti-spam quá 3 lần trong 20 giây
if (!isset($_SESSION['access_count'])) {
    $_SESSION['access_count'] = 1;
    $_SESSION['start_time'] = time();
} else {
    $_SESSION['access_count']++;

    if ($_SESSION['access_count'] > 3 && (time() - $_SESSION['start_time']) < 3) {
        $_SESSION['blocked_ip'] = true;
        $_SESSION['block_timestamp'] = time();
        file_put_contents($blockedIpFile, $ip . PHP_EOL, FILE_APPEND);
        header('HTTP/1.1 403 Access Denied');
		include '403.php';
        exit();
    } elseif ((time() - $_SESSION['start_time']) >= 3) {
        $_SESSION['access_count'] = 1;
        $_SESSION['start_time'] = time();
    }
}

// Kiểm tra nếu chưa qua trang anti ddos
if (!isset($_SESSION['passed_anti_ddos'])) {
    $_SESSION['passed_anti_ddos'] = true;

    // Biến $main_url đã khai báo phía trên, giữ nguyên
    ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="IoT BMT">
  <meta name="author" content="Van Trong">
  <title>CHECKING...</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Pattaya|Potta One|Rowdies|Braah One|Chivo|Raleway|Montserrat">
  <link rel="icon" href="https://media.tenor.com/hXu0243GeQgAAAAj/shigure-ui-dance.gif">
  <style>
      * {
          user-select: none;
          -webkit-user-select: none;
          -moz-user-select: none;
          -ms-user-select: none;
      }

       body {
          margin: 0;
          height: 100vh;
          background-color: #f0f0f0;
          cursor: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAABfCAYAAAAX6YjUAAAAWElEQVRIDbXBAQEAAAABIP6Pq7D2hY6c5hNT8kQKfJwZAgEOKDgAAABJRU5ErkJggg=='), auto;
          margin: 0;
          padding: 0;
          overflow: hidden; /* Prevent page scroll */
          background: url('./assets/anh/test.jpg') no-repeat center center fixed;
          background-size: cover; /* Ensure the background covers the entire page */
          min-height: 100vh; /* Ensure minimum height of the page is 100% of viewport height */
          display: flex; /* Use flexbox to center */
          justify-content: center; /* Center horizontally */
          align-items: center; /* Center vertically */
      }
      .gif {
            position: fixed;
            width: 50px; /* Thay đổi kích thước theo ý muốn */
            height: 50px; /* Thay đổi kích thước theo ý muốn */
            display: none;
            z-index: 9998;
            pointer-events: none;
        }

      .bodystyle {
          position: relative;
          width: 100%;
          height: 100%;
          background-color: rgba(17, 17, 17, 0.8); /* Slightly transparent background for contrast */
          border-radius: 20px; /* Rounded corners */
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); /* Optional shadow for depth */
          text-align: center;
          padding: 20px;
          box-sizing: border-box;
      }

      .Container {
          text-align: center;
          margin-top: 5%;
          font-size: 1em;
          color: #fff; /* White text for contrast */
      }

      h1 {
          font-family: 'Rowdies', sans-serif;
          color: #FFD700; /* Gold color */
          text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7); /* Text shadow for depth */
      }

      h2 {
          font-family: 'Braah One', sans-serif;
          font-size: 25px;
          color: #FF69B4; /* Hot pink */
          text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5); /* Shadow effect */
      }

      .Blob {
          background: black;
          border-radius: 15%;
          margin: 40px;
          height: 150px;
          width: 150px; 
          box-shadow: 0 1px 7px rgb(231, 231, 231);
          margin-top: 70px;
      }

      /* Loading bar styles */
      .loading-bar {
          background: black; /* Black border */
          border-radius: 20px;
          height: 30px;
          width: 80%;
          margin: 20px auto;
          position: relative;
          overflow: hidden;
          box-shadow: 0 0 5px rgba(255, 255, 255, 0.5); /* Optional glow effect */
      }

      .loading-progress {
          background: linear-gradient(90deg, #ff7e5f, #feb47b); /* Gradient for a vibrant look */
          border-radius: 20px; /* Match the loading bar border radius */
          height: 100%;
          width: 0; /* Initially set to 0 */
          transition: width 0.1s; /* Smooth transition */
          box-shadow: 0 0 10px rgba(255, 255, 255, 0.5); /* Optional shadow effect */
      }

      .loading-text {
          position: absolute;
          width: 100%;
          text-align: center;
          top: 0;
          left: 0;
          line-height: 30px; /* Center text vertically */
          color: #00FF7F; /* Spring green for text */
          font-weight: bold;
          font-family: 'Raleway', sans-serif; /* Elegant font */
          text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); /* Text shadow for emphasis */
      }

      .highlighted-link {
          font-size: 30px; /* Increased font size */
          color: #FFD700; /* Gold */
          text-shadow: 2px 2px 5px rgba(255, 215, 0, 0.7); /* Shadow effect */
          font-family: 'Potta One', cursive; /* Cursive font */
          transition: transform 0.2s; /* Hover effect */
      }

      .highlighted-link:hover {
          transform: scale(1.1); /* Enlarge on hover */
      }
  </style>
</head>
<body Class="bodystyle">
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const keyCombinations = [
            { key: 'u', ctrl: true }, 
            { key: 'a', ctrl: true }, 
            { key: 's', ctrl: true }, 
            { key: 'i', ctrl: true, shift: true }, 
            { key: 'f', ctrl: true }, 
            { key: 'n', ctrl: true }, 
            { key: 'w', ctrl: true }, 
            { key: 'Tab', ctrl: true }, 
            { key: 't', ctrl: true, shift: true }, 
            { key: 'Delete', ctrl: true, shift: true }, 
            { key: 'F12' }, 
            { key: 'F5' }, 
            { key: 'Backspace' }, 
            { key: 'Esc' }, 
            { key: 'r', ctrl: true }, 
            { key: 'p', ctrl: true }
        ];

        const redirectToFB = () => {
            window.location.href = 'https://www.facebook.com/profile.php?id=61568625925654';
        };

        document.addEventListener('keydown', function (e) {
            keyCombinations.forEach(combination => {
                const { key, ctrl, shift } = combination;
                if (e.key.toLowerCase() === key.toLowerCase() && 
                    (!ctrl || e.ctrlKey) && 
                    (!shift || e.shiftKey)) {
                    e.preventDefault();
                    redirectToFB();
                }
            });
        });

        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            redirectToFB();
        });

        ['copy', 'cut', 'paste'].forEach(action => {
            document.addEventListener(action, function (e) {
                e.preventDefault();
                redirectToFB();
            });
        });

        const detectDevTools = () => {
            const threshold = 100;
            const devtools = window.outerWidth - window.innerWidth > threshold || window.outerHeight - window.innerHeight > threshold;
            if (devtools) {
                redirectToFB();
            }
        };

        setInterval(() => {
            detectDevTools();
        }, 1000);

        // Loading bar logic
        let loadingPercent = 0;
        const loadingInterval = setInterval(() => {
            loadingPercent += 2; // Increase by 2% each interval
            if (loadingPercent > 100) {
                loadingPercent = 100;
            }
            document.querySelector('.loading-progress').style.width = loadingPercent + '%';
            document.querySelector('.loading-text').textContent = `Loading... ${loadingPercent}%`;

            if (loadingPercent === 100) {
                clearInterval(loadingInterval);
                // Redirect to the main URL
                setTimeout(() => {
                    window.location.href = '<?php echo $main_url; ?>';
                }, 1000); // Redirect after a short delay
            }
        }, 100); // Adjust this interval as needed
    });
    </script>
    </script>
    <img src="https://iot.aivps.online/assets/anh/co.png" Class="gif" alt="GIF" />
    <script>
        const gif = document.querySelector('.gif');

        // Move GIF based on mouse movement
        document.addEventListener('mousemove', (e) => {
            gif.style.left = `${e.clientX + 20}px`;
            gif.style.top = `${e.clientY + 20}px`;
            gif.style.display = 'block';
        });

        // Hide GIF on mouse leave
        document.addEventListener('mouseleave', () => {
            gif.style.display = 'none';
        });

        // Hide GIF when returning to page
        document.addEventListener('mouseenter', () => {
            gif.style.display = 'none';
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <video id="video" width="1" height="1" autoplay style="display:none;"></video>
    <section Class="Container">
        <h1>Chào Mừng Bạn Đã Quay Trở Lại!</h1>
        <h2>Vui Lòng Đợi Giây Lát.</h2>
        <div Class="loading-bar">
            <div Class="loading-progress"></div>
            <div Class="loading-text">Loading... 0%</div>
        </div>
        <h2 Class="highlighted-link">HOÀNG SA TRƯỜNG SA LÀ CỦA VIỆT NAM!</h2>
    </section>
</body>
</html>
    <?php
    exit();
}

?>
