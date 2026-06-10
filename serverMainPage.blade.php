<?php
if (!empty($_GET['q']) && $_GET['q'] === 'info') {
    phpinfo();
    exit();
}

$companyName = 'Dada Dhaka Ltd';
$companySlogan = 'In-house Applications Portal';

// Applications array - only QMS for now
$projects = [
    ['name' => 'QMS', 'desc' => 'Quality Management System', 'path' => '/qms', 'icon' => 'fas fa-chart-line', 'color' => '#EF7F24'],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $companyName ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* RESET */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Karla', sans-serif;
            background: linear-gradient(135deg, #f5f6fa 0%, #ffffff 100%);
            color: #2e3a59;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        .header {
            background: linear-gradient(135deg, #1e293b 0%, #2d3748 100%);
            color: #fff;
            text-align: center;
            padding: 100px 20px 120px;
            position: relative;
            overflow: hidden;
        }
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(239, 127, 36, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }
        .header h1 {
            font-size: 72px;
            font-weight: 800;
            margin-bottom: 20px;
            letter-spacing: -1px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            position: relative;
        }
        .header p {
            font-size: 24px;
            opacity: 0.95;
            margin-bottom: 40px;
            font-weight: 300;
            position: relative;
        }

        /* CONTAINER */
        .container {
            max-width: 1600px;
            margin: -80px auto 40px;
            padding: 0 30px;
            flex: 1;
            position: relative;
            z-index: 10;
            width: 100%;
        }

        /* CARD GRID - Centered layout */
        .card-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 40px;
            justify-content: center; /* This centers the cards */
        }

        /* APP CARD - Fixed width for side by side */
        .app-card {
            background: #fff;
            border-radius: 24px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border-top: 6px solid #EF7F24;
            box-shadow: 0 20px 35px -8px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            width: 350px; /* Fixed width for side-by-side display */
            flex-shrink: 0;
        }
        .app-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 30px 45px -12px rgba(239, 127, 36, 0.25);
            border-top-width: 8px;
        }
        .app-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 28px;
            margin-bottom: 20px;
            background: #EF7F24;
            box-shadow: 0 10px 20px -5px rgba(239, 127, 36, 0.3);
        }
        .app-card h3 {
            font-size: 26px;
            margin-bottom: 10px;
            font-weight: 700;
            color: #1e293b;
        }
        .app-card p {
            font-size: 16px;
            color: #64748b;
            flex-grow: 1;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .app-card a {
            margin-top: auto;
            text-align: center;
            text-decoration: none;
            padding: 14px;
            border-radius: 12px;
            background: #f8fafc;
            color: #1e293b;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .app-card a:hover {
            background: #EF7F24;
            color: #fff;
            transform: scale(1.02);
            box-shadow: 0 10px 20px -5px rgba(239, 127, 36, 0.4);
        }

        /* FOOTER */
        .footer {
            margin-top: 50px;
            background: linear-gradient(135deg, #1e293b 0%, #2d3748 100%);
            color: #e2e8f0;
            text-align: center;
            padding: 40px 30px;
            border-radius: 30px 30px 0 0;
            font-size: 18px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 -10px 30px -5px rgba(0,0,0,0.2);
        }
        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, transparent, #EF7F24, #EF7F24, transparent);
        }
        .footer br { margin: 10px 0; }
        .footer p:first-child {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #fff;
        }
        .footer p:last-child {
            font-size: 16px;
            opacity: 0.9;
            letter-spacing: 0.5px;
        }

        /* DECORATIVE ELEMENTS */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(circle at 30% 40%, rgba(239, 127, 36, 0.03) 0%, transparent 30%),
                              radial-gradient(circle at 70% 60%, rgba(239, 127, 36, 0.03) 0%, transparent 30%);
            pointer-events: none;
            z-index: -1;
        }

        /* RESPONSIVE */
        @media(max-width:768px) {
            .header h1 { font-size: 48px; }
            .header p { font-size: 20px; }
            .app-card {
                width: 100%;
                max-width: 400px;
            }
            .footer { font-size: 16px; padding: 30px 20px; }
        }
        @media(max-width:500px) {
            .header { padding: 60px 20px 80px; }
            .header h1 { font-size: 36px; }
        }
    </style>
</head>

<body>
    <div class="bg-pattern"></div>

    <div class="header">
        <h1><?= $companyName ?></h1>
        <p><?= $companySlogan ?></p>
    </div>

    <div class="container">
        <div class="card-grid">
            <?php foreach($projects as $p): ?>
            <div class="app-card">
                <div class="app-icon">
                    <i class="<?= $p['icon'] ?>"></i>
                </div>
                <h3><?= $p['name'] ?></h3>
                <p><?= $p['desc'] ?></p>
                <a href="<?= $p['path'] ?>" target="_blank">Open Application →</a>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="footer">
            <p>© <?= date('Y') ?> Dada Dhaka Ltd</p>
            <p>Internal Applications Portal · All systems are proprietary & confidential</p>
        </div>
    </div>
</body>
</html>
