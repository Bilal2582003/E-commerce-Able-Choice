<?php
// include header file

include 'header.php';
?>

<div class="admin-content-container">
    <h2 class="admin-heading">Theme</h2>
    <?php
    $id = $_GET['id'];
    $db = new Database();
    $db->select('theme','*',null,"id='{$id}'",null,null);
    $result = $db->getResult();
    if ($result > 0) {
        foreach($result as $row) { ?>
            <form id="updateTheme" class="add-post-form row" method="post" enctype="multipart/form-data">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Color</label>
                        <input type="text" class="form-control color" name="color"
                               value="<?php echo $row['color']; ?>" placeholder="Color Name"/>
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
                    </div>
                    <div class="form-group">
                        <label for="">Client Logo</label>
                        <input type="file" class="new_logo" name="new_logo" />
                        <input type="hidden" class="old_logo" name="old_logo" value="<?php echo $row['logo']; ?>">
                        <img id="image" src="../images/<?php echo $row['logo']; ?>" alt="" width="100px"/>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                        <label for="">Sample Image</label>
                        <input type="file" class="new_sample_image" name="new_sample_image" />
                        <input type="hidden" class="old_sample_image" name="old_sample_image" value="<?php echo $row['image']; ?>">
                        <img id="image" src="../images/<?php echo $row['image']; ?>" alt="" width="100px"/>
                    </div>
                    
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="submit" class="btn add-new" name="submit" value="Update">
                    </div>
                </div>
            </form>
        <?php
        }
    }
    ?>
</div>
<?php
//    include footer file
    include "footer.php";
?>