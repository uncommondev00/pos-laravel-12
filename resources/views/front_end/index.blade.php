<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hardyman Hardware</title>
    <link rel="icon" href="/img/hardyman-icon.png" type="image/x-icon" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
        }

        .hardyman-hero {
            width: 100%;
            height: 100vh;
            background: url('/img/hero1.png') center/cover no-repeat;
            position: relative;
        }

        .hardyman-hero2 {
            width: 100%;
            height: 300px;
            background: url('/img/hero2.png') center/cover no-repeat;
            position: relative;
        }

        .hardyman-logo {
            position: absolute;
            top: -120px;
            left: 20px;
            z-index: 10;
        }

        .hardyman-logo img {
            width: 250px;
            max-width: 80vw;
            height: auto;
            object-fit: contain;
        }

        .hardyman-footer {
            background: #1e293b;
            color: #fff;
            padding: 18px 10px;
            text-align: center;
        }

        .hardyman-footer-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .hardyman-footer span,
        .hardyman-footer a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* MOBILE RESPONSIVE */
        @media (max-width: 768px) {
            .hardyman-hero {
                height: auto;
                aspect-ratio: 16 / 9;
                background-size: contain;
            }

            .hardyman-hero2 {
                height: auto;
                aspect-ratio: 16 / 6;
                background-size: contain;
            }

            .hardyman-logo {
                top: -50px;
                left: 10px;
            }

            .hardyman-logo img {
                width: 120px;
            }

            .hardyman-footer {
                font-size: 0.9rem;
            }

            .hardyman-footer-container {
                flex-direction: column;
                gap: 12px;
            }
        }
    </style>
</head>

<body>
    <!-- Hero Section 1 -->
    <section class="hardyman-hero"></section>

    <!-- Hero Section 2 with Overlapping Logo -->
    <div style="position: relative;">
        <section class="hardyman-hero2"></section>
        <div class="hardyman-logo">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" />
        </div>
    </div>

    <!-- Footer -->
    <footer class="hardyman-footer">
        <div class="hardyman-footer-container">
            <span><i class="fa">&#xf095;</i> 0987654323</span>
            <span><i class="fa">&#xf041;</i> National Highway, Odiong, Roxas, Oriental Mindoro</span>
            <a href="mailto:hardymam_hardware@yahoo.com">
                <span><i class="fa">&#xf0e0;</i> hardymam_hardware@yahoo.com</span>
            </a>
            <a href="https://www.facebook.com/hardymanhardware" target="_blank">
                <span><i class="fa">&#xf09a;</i> facebook.com/hardymanhardware</span>
            </a>
        </div>
    </footer>
</body>

</html>
