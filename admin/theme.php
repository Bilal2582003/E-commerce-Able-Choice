<?php
// include header file
include 'header.php'; ?>
<div class="admin-content-container">
    <h2 class="admin-heading">All Theme</h2>
    <a class="add-new pull-right" href="add_theme.php">Add New</a>
    <?php
    $limit = 3;
    $db = new Database();
    $db->select('theme','*',null,null,'theme.id DESC',$limit);
    $result = $db->getResult();
    if (count($result) > 0) { ?>
        <table class="table table-striped table-hover table-bordered">
            <thead>
            <th>Color</th>
            <th>Theme Logo</th>
            <th>Sample Image</th>
            <th>Action</th>
            </thead>
            <tbody>
            <?php foreach($result as $row) { ?>
                <tr>
                    <td><?php echo $row['color']; ?></td>
                    <td><img src="../images/<?php echo $row['logo']; ?>" width="150px" height="70px" /></td>
                    <td><img src="../images/<?php echo $row['image']; ?>" width="200px" height="120px" /></td>
                    <td>
                        <a href="edit_theme.php?id=<?php echo $row['id'];  ?>"><i class="fa fa-edit"></i></a>
                        <a class="delete_theme" href="javascript:void();" data-id="<?php echo $row['id'];  ?>"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php }else{ ?>
        <div class="not-found">!!! No Theme Found !!!</div>
    <?php    } ?>
    <div class="pagination-outer">
        <?php echo $db->pagination('theme',null,null,$limit); ?>
    </div>
</div>

<?php
//    include footer file
    include "footer.php";
?>
