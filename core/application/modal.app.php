<?php
// SmallURL Modal Class
class modal {
	function create($title,$message,$buttons = false) {
		global $_MODAL;
		$modal = array();
		$modal['title'] = $title;
		$modal['id'] = "modal_".md5($title.$message);
		$modal['message'] = $message;
		if (!$buttons) {
			$modal['buttons'] = array("Ok"=>"close");
		} else {
			$modal['buttons'] = $buttons;
		}
		$_MODAL[] = $modal;
		return $modal['id'];
	}
	function html() {
		global $_MODAL;
		if (count($_MODAL) > 0) {
			foreach ($_MODAL as $modal) {
				?>
							<!-- Modal -->
				<div class="modal fade" id="<?php echo $modal['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				  <div class="modal-dialog">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel"><?php echo $modal['title']; ?></h4>
					  </div>
					  <div class="modal-body">
						<?php echo $modal['message']; ?>
					  </div>
					  <div class="modal-footer">
						<?php
							foreach ($modal['buttons'] as $val => $func) {
								$class = "default";
								if (strpos($val,'%') !== FALSE) {
									$class = substr($val,0,strpos($val,'%'));
									$val = substr($val,strpos($val,'%')+1);
								}
								$func = str_replace("%id%",$modal['id'],$func);
								if ($func == "close") {
									echo '<button type="button" class="btn btn-'.$class.'" data-dismiss="modal">'.$val.'</button>';
								} else {
									echo '<button type="button" onclick="'.$func.'" class="btn btn-'.$class.'">'.$val.'</button>';
								}
							}
						?>
					  </div>
					</div><!-- /.modal-content -->
				  </div><!-- /.modal-dialog -->
				</div><!-- /.modal -->
				<?php
			}
		}
	}
	function javascript() {
		global $_MODAL;
		if (count($_MODAL) > 0) {
			?>
			<script>
			// Cheeky hack to make it so people cant scroll when its open.
			$('#modal_').on('show.bs.modal', function () {
			  $('html').css('overflow','hidden');
			  $('body').css('overflow','hidden');
			})
			$('#modal_').on('hide.bs.modal', function () {
			  $('body').css('overflow','visible');
			  $('html').css('overflow','visible');
			  alert('hide');
			})
			</script>
			<?php
		}
	}
}
$_MODAL = array();
$modal = new modal();
?>