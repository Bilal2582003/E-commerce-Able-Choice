<div id="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php
                $db = new Database();
                $db->select('options', 'site_name,footer_text,site_desc,contact_phone,contact_email,contact_address', null, null, null, null);
                $footer = $db->getResult(); ?>
                <h3><?php echo $footer[0]['site_name']; ?></h3>
                <p><?php echo $footer[0]['site_desc']; ?></p>
            </div>
            <div class="col-md-3">
                <h3>Categories</h3>
                <ul class="menu-list">
                    <?php
                    $db = new Database();
                    $db->select('sub_categories', '*', null, 'cat_products > 0 AND show_in_footer ="1"', null, null);
                    $result = $db->getResult();
                    if (count($result) > 0) {
                        foreach ($result as $res) { ?>
                            <li><a
                                    href="category.php?cat=<?php echo $res['sub_cat_id']; ?>"><?php echo $res['sub_cat_title']; ?></a>
                            </li>
                        <?php }
                    } ?>
                </ul>
            </div>
            <div class="col-md-3">
                <h3>Useful Links</h3>
                <ul class="menu-list">
                    <li><a href="<?php echo $hostname; ?>">Home</a></li>
                    <li><a href="all_products.php">All Products</a></li>
                    <li><a href="latest_products.php">Latest Products</a></li>
                    <li><a href="popular_products.php">Popular Products</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h3>Contact Us</h3>
                <ul class="menu-list">
                    <?php if (!empty($footer[0]['contact_address'])) { ?>
                        <li><i class="fa fa-home"></i><span>: <?php echo $footer[0]['contact_address']; ?></span></li>
                    <?php } ?>
                    <?php if (!empty($footer[0]['contact_phone'])) { ?>
                        <li><i class="fa fa-phone"></i><span>: <?php echo $footer[0]['contact_phone']; ?></span></li>
                    <?php } ?>
                    <?php if (!empty($footer[0]['contact_email'])) { ?>
                        <li><i class="fa fa-envelope"></i><span>: <?php echo $footer[0]['contact_email']; ?></span></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-md-12">
                <span><?php echo $footer[0]['footer_text'] ?> | Created by Muhammad Bilal Raza</span>
            </div>
        </div>
    </div>
</div>
<script src="js\jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="js\bootstrap.min.js"></script>
<script src="js\actions.js"></script>
<!--okzoom Plugin-->
<script src="js/okzoom.min.js" type="text/javascript"></script>
<!--owl carousel plugin-->
<script type="text/javascript" src="js/owl.carousel.js"></script>
<script>

    <?php
    echo "document.body.className = ''; ";
    if ($_SESSION['theme']) {
        $className = $_SESSION['theme']['color'];
        echo "document.body.classList.add('" . $className . "');";
    }
    ?>

    $('.product_description').jqte({
        link: false,
        unlink: false,
        color: false,
        source: false,
    });

</script>
<script>
    $(document).ready(function () {

        $(".setTheme").on("click", function () {
            // alert("this")
            var themeId = $(this).data('id'); // Get the theme ID from the data-id attribute
            // Send AJAX request to update the theme for the user
            $.ajax({
                url: 'php_files/user.php', // The PHP file that handles the update
                type: 'POST',
                data: {
                    theme_id: themeId, // Send the theme_id to the server
                    updateTheme: "updateTheme"
                },
                success: function (response) {
                    var data = JSON.parse(response); // Parse the JSON response
                    if (data.success) {
                        alert("Theme Updated Successfully"); // Show success message
                        // Optionally, update the UI or reload page
                        window.location.reload();
                    } else {
                        alert(data.error); // Show error message if something went wrong
                    }
                },
                error: function () {
                    alert('Request failed. Please try again.');
                }
            });
        })

        $('#product-img').okzoom({
            width: 200,
            height: 200,
            scaleWidth: 800
        });

        $('.banner-carousel').owlCarousel({
            loop: true,
            margin: 0,
            responsiveClass: true,
            navText: ["", ""],
            responsive: {
                0: {
                    items: 1,
                    nav: true

                },
                600: {
                    items: 1,
                    nav: true
                },
                1000: {
                    items: 1,
                    nav: true,
                    loop: false,
                    margin: 10
                }
            }
        });

        $('.popular-carousel').owlCarousel({
            loop: true,
            margin: 0,
            responsiveClass: true,
            navText: ["", ""],
            responsive: {
                0: {
                    items: 1,
                    nav: true

                },
                600: {
                    items: 2,
                    nav: true
                },
                800: {
                    items: 4,
                    nav: true
                },
                1000: {
                    items: 5,
                    nav: true,
                    loop: false,
                    margin: 10
                }
            }
        });

        $('.latest-carousel').owlCarousel({
            loop: true,
            margin: 0,
            responsiveClass: true,
            navText: ["", ""],
            responsive: {
                0: {
                    items: 1,
                    nav: true

                },
                600: {
                    items: 2,
                    nav: true
                },
                800: {
                    items: 3,
                    nav: true
                },
                1000: {
                    items: 4,
                    nav: true,
                    loop: false,
                    margin: 5
                }
            }
        });
    });

</script>

</body>

</html>