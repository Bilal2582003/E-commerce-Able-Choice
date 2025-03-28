<?php
include 'config.php';
session_start();
if(isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'user') {
    $title = "Theme";
    include 'header.php'; ?>
    <style>
        .specialContainer {
            margin: 10px;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }

        .column {
            width: 50%;
            padding: 10px;
        }

        .card-wrapper {
            text-decoration: none;
            display: block;
        }

        .card {
            background-position: center;
            background-size: cover;
            height: 320px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            border: 5px solid black;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            border-radius: 20px
        }

        .card h4 {
            color: white;
            background: rgba(0, 0, 0, 0.725);
            backdrop-filter: blur(5px);
            padding: 10px 50px;
            border-radius: 20px 20px 0 0;
            transition: all 0.3s ease;
            margin-bottom: 0;
            font-size: 1.5rem;
            font-weight: 500;
            z-index: 2;
            position: absolute;
        }

        .card:hover {
            border-width: 10px;
        }

        .card:hover h4 {
            border-radius: 30px;
            margin-bottom: 10px;
        }


        @media (max-width: 768px) {
            .column {
                width: 100%;
            }
        }
    </style>
    <div class="specialContainer">
        <?php
        $db = new Database();
        $db->select("theme");
        $response = $db->getResult();
        if (!empty($response)) {
            if (count($response) > 1) {
                foreach ($response as $res) {
                    $db->select("user", '*', null, 'user_id = ' . $_SESSION['user_id']);
                    $result = $db->getResult();
                    if ($result[0]['theme_id'] == $res['id']) {
                        $class = 'style="background-color:green" ';
                        $value = 'SELECTED';
                    } else {
                        $class = 'class="setTheme" ';
                        $value = 'SET';
                    }
                    // Sanitize output for image URL
                    $image = htmlspecialchars($res['image'], ENT_QUOTES, 'UTF-8');
                    ?>

                    <div class="column">
                        <div class="card-wrapper" style="cursor:pointer">
                            <div class="card" style="background-image: url(images/<?php echo $image ?>)">
                                <h4 data-id="<?php echo $res['id'] ?>" <?php echo $class ?>><?php echo $value ?></h4>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
        }
        ?>
        <!-- <div class="column">
        <a href="#" class="card-wrapper">
            <div class="card"
                style="background-image: url(https://images.pexels.com/photos/2638019/pexels-photo-2638019.jpeg?auto=compress&cs=tinysrgb&w=600);">
                <h4>Latte</h4>
            </div>
        </a>
    </div>
    <div class="column">
        <a href="#" class="card-wrapper">
            <div class="card"
                style="background-image: url(https://images.pexels.com/photos/1627933/pexels-photo-1627933.jpeg?auto=compress&cs=tinysrgb&w=600);">
                <h4>Macchiato</h4>
            </div>
        </a>
    </div>
    <div class="column">
        <a href="#" class="card-wrapper">
            <div class="card"
                style="background-image: url(https://images.pexels.com/photos/2956954/pexels-photo-2956954.jpeg?auto=compress&cs=tinysrgb&w=600);">
                <h4>Cortado</h4>
            </div>
        </a>
    </div> -->
    </div>
    <?php include 'footer.php';
}else{
    header("Location: " . $hostname);
}