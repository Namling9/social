
<!--
<div class="login-box">
        <div class="login-header">
            <header>Login</header>
        </div>
        <div class="input-box">
            <input type="text" class="input-field" placeholder="Email" autocomplete="off" required>
        </div>
        <div class="input-box">
            <input type="password" class="input-field" placeholder="Password" autocomplete="off" required>
        </div>
        <div class="forgot">
            <section>
                <a href="#">Forgot password</a>
            </section>
        </div>
        <div class="input-submit">
            <button class="submit-btn" id="submit"></button>
            <label for="submit">Sign In</label>
        </div>
        !-->
<?php
include ('session.php');

// Check if the user is an admin.
if ($_SESSION['role'] !== 'admin') {
    header('Location: home.php');
    exit();
}

include("includes/database.php");

// Function to delete comments
if (isset($_GET['delete_comment'])) {
    $comment_id = $_GET['delete_comment'];
    mysqli_query($conn, "DELETE FROM comments WHERE comment_id='$comment_id'");
    header("Location: admin.php");
}

// Function to delete photos (posts)
if (isset($_GET['delete_photo'])) {
    $post_id = $_GET['delete_photo'];
    mysqli_query($conn, "DELETE FROM post WHERE post_id='$post_id'");
    header("Location: admin.php");
}

function time_stamp($session_time) {
    $time_difference = time() - $session_time;
    $seconds = $time_difference;
    $minutes = round($time_difference / 60);
    $hours = round($time_difference / 3600);
    $days = round($time_difference / 86400);
    $weeks = round($time_difference / 604800);
    $months = round($time_difference / 2419200);
    $years = round($time_difference / 29030400);

    if ($seconds <= 60) {
        return "$seconds seconds ago";
    } elseif ($minutes <= 60) {
        return $minutes == 1 ? "one minute ago" : "$minutes minutes ago";
    } elseif ($hours <= 24) {
        return $hours == 1 ? "one hour ago" : "$hours hours ago";
    } elseif ($days <= 7) {
        return $days == 1 ? "one day ago" : "$days days ago";
    } elseif ($weeks <= 4) {
        return $weeks == 1 ? "one week ago" : "$weeks weeks ago";
    } elseif ($months <= 12) {
        return $months == 1 ? "one month ago" : "$months months ago";
    } else {
        return $years == 1 ? "one year ago" : "$years years ago";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>

<body>
    <div id="header">
        <div class="head-view">
            <ul>
                <li><a href="home.php" title="social network"><b>Social Network</b></a></li>
                <li><a href="logout.php" title="Log out"><button class="btn-sign-in" value="Log out">Log out</button></a></li>
            </ul>
        </div>
    </div>

    <div id="container">
        <div id="admin-panel">
            <h1>Admin Panel</h1>

            <h2>Manage Comments</h2>
            <?php
            $query = mysqli_query($conn, "SELECT * FROM comments LEFT JOIN user ON user.user_id = comments.user_id ORDER BY comment_id DESC");
            while ($row = mysqli_fetch_array($query)) {
                $comment_id = $row['comment_id'];
                $content_comment = $row['content_comment'];
                $username = $row['username'];
                $profile_picture = $row['profile_picture'];
                $time = $row['created'];
            ?>
                <div class="comment-display" id="comment-<?php echo $comment_id ?>">
                    <div class="user-comment-name"><img src="<?php echo $profile_picture; ?>">&nbsp;&nbsp;&nbsp;<?php echo $username; ?><b class="time-comment"><?php echo time_stamp($time); ?></b></div>
                    <div class="comment"><?php echo $content_comment; ?></div>
                    <div class="delete-comment">
                        <a href="admin.php?delete_comment=<?php echo $comment_id; ?>" title="Delete this comment"><button class="btn-delete">Delete Comment</button></a>
                    </div>
                </div>
                <br />
            <?php
            }
            ?>

            <h2>Manage Photos</h2>
            <?php
            $query = mysqli_query($conn, "SELECT * FROM post LEFT JOIN user ON user.user_id = post.user_id ORDER BY post_id DESC");
            while ($row = mysqli_fetch_array($query)) {
                $post_id = $row['post_id'];
                $posted_by = $row['firstname'] . " " . $row['lastname'];
                $location = $row['post_image'];
                $profile_picture = $row['profile_picture'];
                $content = $row['content'];
                $time = $row['created'];
            ?>
                <div class="photo-display" id="post-<?php echo $post_id ?>">
                    <div class="profile-pics">
                        <img src="<?php echo $profile_picture; ?>">
                        <b><?php echo $posted_by; ?></b>
                        <strong><?php echo time_stamp($time); ?></strong>
                    </div>
                    <br />
                    <div class="post-content">
                        <p><?php echo $content; ?></p>
                        <center>
                            <img src="<?php echo $location; ?>">
                        </center>
                    </div>
                    <div class="delete-photo">
                        <a href="admin.php?delete_photo=<?php echo $post_id; ?>" title="Delete this photo"><button class="btn-delete">Delete Photo</button></a>
                    </div>
                </div>
                <br />
            <?php
            }
            ?>
        </div>
    </div>
</body>

</html>
