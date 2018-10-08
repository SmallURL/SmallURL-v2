<?php
class controller extends controller_base {

    private $category = "users";

   function __construct() {
       parent::__construct();
       global $db;
       $this->db = $db;
   }

   public function home($title = "Users") {
      $this->generate("Default");
    }
    public function smallurl() {
      $this->generate("SmallURL");
    }

    public function smallpaste() {
      $this->generate("SmallPaste");
    }

    public function smallimage() {
      $this->generate("SmallImage");
    }

    private function generate($mode) {

        $this->theme->header("Users (".htmlentities($mode).")");


        $this->theme->sidebar($this->category);

        $account = new account();

        include(LAYOUT."users_controller/home.tmpl");
        include(LAYOUT."users_controller/modal.tmpl");
        $this->theme->footer();


    }

    public function post() {
      $account = new account();
      $action = $this->parseURL()[2];
      if($action == 'disable_user') {
        if($this->parseURL()[3] == $_SESSION['crsf']) {
          if($account->haspriv("admin_users_modify")) {
            $uid = $this->parseURL()[4];
              if(is_numeric($uid)) {
                $account = new account();
                $account->disableUser($this->parseURL()[4]);
              }
            header("Location: /users/");
          } else {
            echo "Access Denied";
          }
        } else {
          echo "Invalid CSRF Token";
        }
      } else if($action == 'enable_user') {
        if($this->parseURL()[3] == $_SESSION['crsf']) {
          if($account->haspriv("admin_users_modify")) {
            $uid = $this->parseURL()[4];
              if(is_numeric($uid)) {
                $account = new account();
                $account->enableUser($this->parseURL()[4]);
              }
            } else {
              echo "Access denied";
            }
          } else {
            echo "Invalid CSRF Token";
          }
        header("Location: /users/");
      } else if($action == 'show_services') {
        echo "<script>$('.modal-title').html('List of services');</script>"; ?>
        <table class="table table-hover" style='text-align:center;'><thead><td> Service: </td><td> Date Active </td> <td> Remove? </td> <tbody> <tr style='text-align: center;'> <td> <img src='http://static.<?=SITE_URL?>/img/small_logo_black.png' style='width: 220px'> </td> <td style='vertical-align:middle'> Active Since: Forever </td> <td style='vertical-align:middle'> <button type="button" class="btn btn-danger disabled"> x </button> </td> </td> </table> <?php
      } else if($action == 'reset_password') {
        if($account->haspriv("admin_users_modify")) {
      echo "<script>$('.modal-title').html('Change users password');</script>";
      ?>
      <form class="form-horizontal" method='post' action='/users/post/parse_pass_reset/<?=$_SESSION['crsf'];?>/<?=$this->parseURL()[4]?>' role="form">

        <div class="form-group">
          <label for="password1" class="col-sm-3 control-label">Password</label>
          <div class="col-sm-7">
            <input type="password" name='password' class="form-control" id="password1" placeholder="Password">
          </div>
          <label for="password1" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="password2" class="col-sm-3 control-label">Verify</label>
          <div class="col-sm-7">
            <input type="password" name='password1' class="form-control" id="password2" placeholder="Password">
          </div>
          <label for="password2" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-6 col-sm-2">
            <button type="submit" class="btn btn-success" style='margin-left:20px;'>Update Password</button>
          </div>
        </div>
      </form> <?php } else { echo "Access Denied";}
      } else if($action == 'show_images') {
        echo "<script>$('.modal-title').html('List of Images');</script>";
        echo "<h4> SmallImages dont exist D:";
      } else if($action == 'show_pastes') {
        echo "<script>$('.modal-title').html('List of Pastes');</script>";
        echo "<h4> SmallPastes dont exist D:";
      } else if($action == 'show_urls') {
        echo "<script>$('.modal-title').html('List of URLS');</script>";
         if(isset($this->parseURL()[3]) && $this->parseURL()[3] == $_SESSION['crsf']) {
            if(isset($this->parseURL()[4]) && is_numeric($this->parseURL()[4])) {
                if($account->haspriv("can_smallurl")) {
                    echo "<div style='height: 400px;width: 100%;overflow-y:scroll;'>";
                    ?>
                    <table class="table table-hover table-fixed-header" style='text-align:center;'> <thead> <tr ><th>Short URL</th><th>Created On:</th><th> Uses: </th> <th> IP: </th> <th> RM: </th></tr> </thead> <tbody>
                    <?php
                    global $db;

                    $query = $this->db->prepare("SELECT * FROM `smallurl_entries` WHERE `user` = :uid");
                    $params = array(":uid" => $this->parseURL()[4],);
                    $query->execute($params);

                    while ($url = $query->fetchObject()) {

                        if($url->ipaddr == '' ) {
                            $url->ipaddr = '0.0.0.0';
                        }
                        ?>
                        <tr><td><a href="http://surl.im/<?=$url->short;?>">surl.im/<?=$url->short;?></a></td><td><?=gmdate("d-m-Y H:i:s",$url->stamp)?></td><td> <span class="badge"><?=$url->uses;?></span> </td> <td> <span class="label label-success"><?=$url->ipaddr;?> </span> </td> <td> <button type="button" class=" btn btn-danger disabled"> X </button> </td></tr>
                        <?php } ?></tbody> </table>
                    <?php
                    echo "</div>";
                } else {
                    echo "Access Denied!";
                }
            } else {
                echo "Invalid UID Specified!";
            }
        } else {
            echo "Invalid CRSF Token!";
        }
      } else if ($action == 'parse_pass_reset') {
         if(isset($this->parseURL()[3]) && $this->parseURL()[3] == $_SESSION['crsf']) {
            if(isset($this->parseURL()[4]) && is_numeric($this->parseURL()[4])) {
              if($account->haspriv("admin_users_modify")) {
                if(isset($_POST['password']) && isset($_POST['password1']) && $_POST['password'] == $_POST['password1']) {
                  if($_POST['password'] != '') {
                    $_SESSION['password_javascript'] = "<script> alert('Updated the account password.'); </script>";
                    echo $account->SetPassword($this->parseURL()[4], $_POST['password']);
                  } else {
                    $_SESSION['password_javascript'] = "<script> alert('Password reset: Invalid Password entered.'); </script>";
                  }
                } else {
                  $_SESSION['password_javascript'] = "<script> alert('Password reset: Passwords didn't match.'); </script>";
                }
                header("Location: /users/");
              } else {
                echo "Access Denied";
              }
            } else {
              echo "Invalid uid";
            }
          } else {
            echo "invalid crsf";
          }
      } else if ($action == 'edit_user') {
         if(isset($this->parseURL()[3]) && $this->parseURL()[3] == $_SESSION['crsf']) {
            if(isset($this->parseURL()[4]) && is_numeric($this->parseURL()[4])) {
              if($account->haspriv("admin_users_modify")) {
    echo "<script>$('.modal-title').html('Edit a user');</script>";

        $info = $account->userInfo($this->parseURL()[4]);
        ?>
<div style="height: 400px;width: 100%;overflow-y:scroll;">
      <form class="form-horizontal" method='post' action='/users/post/parse_account_update/<?=$_SESSION['crsf'];?>/<?=$this->parseURL()[4]?>' role="form">

        <div class="form-group">
          <label for="name" class="col-sm-3 control-label">Name</label>
          <div class="col-sm-7">
            <input type="text" name='name' class="form-control" id="name" value='<?=htmlentities($info->name);?>' placeholder="Name">
          </div>
          <label for="name" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="username" class="col-sm-3 control-label">Username</label>
          <div class="col-sm-7">
            <input type="text" name='username' class="form-control" id="username" value='<?=htmlentities($info->username);?>' placeholder="Username">
          </div>
          <label for="username" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">E-Mail</label>
          <div class="col-sm-7">
            <input type="email" name='email' class="form-control" id="email" value='<?=htmlentities($info->email);?>' placeholder="E-Mail">
          </div>
          <label for="email" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="lastlogin" class="col-sm-3 control-label">Last Login:</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" id="lastlogin" value="<?= gmdate("D M j G:i:s T Y", $info->laststamp);?>" disabled>
          </div>
          <label for="lastlogin" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="lastip" class="col-sm-3 control-label">Last IP:</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" id="lastip"  value="<?=$info->lastip;?>" disabled>
          </div>
          <label for="lastip" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="registerdate" class="col-sm-3 control-label">Register Date:</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" id="registerdate" value="<?= gmdate("D M j G:i:s T Y", $info->regstamp);?>"  disabled>
          </div>
          <label for="registerdate" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="regsiterip" class="col-sm-3 control-label">Register IP:</label>
          <div class="col-sm-7">
            <input type="text" class="form-control" id="regsiterip"  value="<?=$info->regip;?>" disabled>
          </div>
          <label for="regsiterip" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="admin" class="col-sm-3 control-label">Admin</label>
          <div class="col-sm-7">
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-danger <?php if($info->admin): echo "active"; endif; ?>">
                <input type="radio" name="admin" value='yes' id="option1" <?php if($info->admin): echo "checked"; endif; ?>> Yes
              </label>
              <label class="btn btn-danger <?php if(!$info->admin): echo "active"; endif; ?>">
                <input type="radio" name="admin" value='no' id="option2" <?php if(!$info->admin): echo "checked"; endif; ?>> No
              </label>
            </div>
          </div>
          <label for="admin" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="hide" class="col-sm-3 control-label">Hide Geo</label>
          <div class="col-sm-7">
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-primary <?php if($info->hidegeo): echo "active"; endif; ?>">
                <input type="radio" name="hide" value='hide' id="option1" <?php if($info->hidegeo): echo "checked"; endif; ?>> Hide
              </label>
              <label class="btn btn-primary <?php if(!$info->hidegeo): echo "active"; endif; ?>">
                <input type="radio" name="hide" value='nohide' id="option2" <?php if(!$info->hidegeo): echo "checked"; endif; ?>> Don't Hide
              </label>
            </div>
          </div>
          <label for="hide" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="private" class="col-sm-3 control-label">Auto Private</label>
          <div class="col-sm-7">
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-primary <?php if($info->autopriv): echo "active"; endif; ?>">
                <input type="radio" name="private" value='enabled' id="option1" <?php if($info->autopriv): echo "checked"; endif; ?>> Enabled
              </label>
              <label class="btn btn-primary <?php if(!$info->autopriv): echo "active"; endif; ?>">
                <input type="radio" name="private" value='disabled' id="option2" <?php if(!$info->autopriv): echo "checked"; endif; ?>> Disabled
              </label>
            </div>
          </div>
          <label for="private" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="safe" class="col-sm-3 control-label">Auto Safe</label>
          <div class="col-sm-7">
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-primary <?php if($info->autosafe): echo "active"; endif; ?>">
                <input type="radio" name="safe" value='enabled' id="option1" <?php if($info->autosafe): echo "checked"; endif; ?>> Enabled
              </label>
              <label class="btn btn-primary <?php if(!$info->autosafe): echo "active"; endif; ?>">
                <input type="radio" name="safe" value='disabled' id="option2" <?php if(!$info->autosafe): echo "checked"; endif; ?>> Disabled
              </label>
            </div>
          </div>
          <label for="safe" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <label for="disabled" class="col-sm-3 control-label">Disabled</label>
          <div class="col-sm-7">
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-warning <?php if(!$info->enabled): echo "active"; endif; ?>">
                <input type="radio" name="disabled" value='yes' id="option1" <?php if(!$info->enabled): echo "checked"; endif; ?>> Yes
              </label>
              <label class="btn btn-warning <?php if($info->enabled): echo "active"; endif; ?>">
                <input type="radio" name="disabled" value='no' id="option2" <?php if($info->enabled): echo "checked"; endif; ?>> No
              </label>
            </div>
          </div>
          <label for="disabled" class="col-sm-2 control-label"></label>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-8 col-sm-2">
            <button type="submit" class="btn btn-success" style=''>Update Account</button>
          </div>
        </div>
      </form>
    </div>
      <?php
          } else {
            echo "Access Denied.";
          }
        } else {
          echo "Invalid UID";
        }
      } else {
        echo "Invalid CRSF token";
      }
    } else if($action == 'parse_account_update') {
      print_r($_POST);
    }
  }
}
?>