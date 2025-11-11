<?php
require 'function.php';
require 'cek.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linus Express - Universitas Sumatera Utara</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="logo-container">
            <img src="./images/logo.png" alt="Logo USU" class="logo">
            <div class="logo-text">
                <h1>Universitas</h1>
                <h1>Sumatera Utara</h1>
            </div>
        </div>
    </header>

    <?php
    require 'sidebar.php';
    ?>

        <div class="main-content">
            <div class="linus-banner">
                <img src="images/linus-banner.png" alt="Banner Linus Express">
            </div>

            <div class="tribox">
                <div class="content">
                    <div class="info-box">
                        <div class="info-header">
                            <h3>Informasi</h3>
                            <div class="active-bus">
                                <span class="green-square"></span>
                                <p>2 Bus sedang aktif sekarang</p>
                            </div>
                        </div>
                        <p>Lihat detail jadwal dan rute? Klik di sini.</p>
                        <a href="detail.php" class="detail-button">Detail</a>
                    </div>

                    <div class="feedback-box">
                        <p>Berikan Feedback anda disini.</p>
                        <a href="cs.html" class="cs-button"><img src="./images/cs-icon.png" alt="Customer Service"
                                class="cs-icon"> Customer Service</a>
                    </div>
                </div>

                <div class="map-container">
                    <iframe src="map.html" title="Peta Tracking Bus Linus"></iframe>
                </div>
            </div>
        </div>
    </div>

    <?php
    require 'footer.php';
    ?>
</body>

</html>